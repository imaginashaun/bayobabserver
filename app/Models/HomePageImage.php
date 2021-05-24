<?php

namespace App\Models;

use App\Helpers\Files\Storage\StorageDisk;
use App\Models\Scopes\ActiveScope;
use App\Observers\SliderImageObserver;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Larapen\Admin\app\Models\Traits\Crud;
use Larapen\Admin\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Prologue\Alerts\Facades\Alert;

class HomePageImage extends BaseModel
{
    use Crud, Sluggable, SluggableScopeHelpers, HasTranslations;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'home_page_images';

    public $translatable = ['title'];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    // protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    // public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'picture',
        'title'
    ];
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'slug_or_name',
            ],
        ];
    }
    protected static function boot()
    {
        parent::boot();

        Page::observe(SliderImageObserver::class);

        static::addGlobalScope(new ActiveScope());
    }

    public function getPictureAttribute()
    {
        if (!isset($this->attributes) || !isset($this->attributes['picture'])) {
            return null;
        }

        $value = $this->attributes['picture'];

        $disk = StorageDisk::getDisk();

        if (!$disk->exists($value)) {
            $value = null;
        }
        return $value;
    }

    public function setPictureAttribute($value)
    {
        $disk = StorageDisk::getDisk();
        $attribute_name = 'picture';
        $destination_path = 'app/page';

        // If the image was erased
        if (empty($value)) {
            // delete the image from disk
            $disk->delete($this->picture);

            // set null in the database column
            $this->attributes[$attribute_name] = null;

            return false;
        }

        // Check the image file
        if ($value == url('/')) {
            $this->attributes[$attribute_name] = null;

            return false;
        }

        // If laravel request->file('filename') resource OR base64 was sent, store it in the db
        try {
            if (fileIsUploaded($value)) {
                // Get file extension
                $extension = getUploadedFileExtension($value);
                if (empty($extension)) {
                    $extension = 'jpg';
                }

                // Image quality
                $imageQuality = 100;

                // Image default dimensions
                $width = (int)config('larapen.core.picture.otherTypes.bgHeader.width', 2000);
                $height = (int)config('larapen.core.picture.otherTypes.bgHeader.height', 1000);

                // Init. Intervention
                $image = Image::make($value);

                // Get the image original dimensions
                $imgWidth = (int)$image->width();
                $imgHeight = (int)$image->height();

                // Fix the Image Orientation
                if (exifExtIsEnabled()) {
                    $image = $image->orientate();
                }

                // If the original dimensions are higher than the resize dimensions
                // OR the 'upsize' option is enable, then resize the image
                if ($imgWidth > $width || $imgHeight > $height) {
                    // Resize
                    $image = $image->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }

                // Encode the Image!
                $image = $image->encode($extension, $imageQuality);

                // Generate a filename.
                $filename = md5($value . time()) . '.' . $extension;

                // Store the image on disk.
                $disk->put($destination_path . '/' . $filename, $image->stream()->__toString());

                // Save the path to the database
                $this->attributes[$attribute_name] = $destination_path . '/' . $filename;
            } else {
                // Retrieve current value without upload a new file.
                if (!Str::startsWith($value, $destination_path)) {
                    $value = $destination_path . last(explode($destination_path, $value));
                }
                $this->attributes[$attribute_name] = $value;
            }
        } catch (\Exception $e) {
            Alert::error($e->getMessage())->flash();
            $this->attributes[$attribute_name] = null;

            return false;
        }
    }
}
