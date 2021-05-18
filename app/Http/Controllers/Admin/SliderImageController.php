<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ImageSliderRequest as StoreRequest;
use App\Http\Requests\Admin\ImageSliderRequest as UpdateRequest;
use Larapen\Admin\app\Http\Controllers\PanelController;

class SliderImageController extends PanelController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\SliderImage');
        $this->xPanel->setRoute(admin_uri('sliderimages'));
        $this->xPanel->setEntityNameStrings("Slider Image", "Slider Images");


      //  $this->xPanel->addButtonFromModelFunction('top', 'bulk_delete_btn', 'bulkDeleteBtn', 'end');

        // Filters
        // -----------------------
        $this->xPanel->disableSearchBar();
        // -----------------------



        /*
        |--------------------------------------------------------------------------
        | COLUMNS AND FIELDS
        |--------------------------------------------------------------------------
        */
        // COLUMNS
        $this->xPanel->addColumn([
            'name'  => 'id',
            'label' => '',
            'type'  => 'checkbox',
            'orderable' => false,
        ]);

        $this->xPanel->addColumn([
            'name'  => 'title',
            'label' => mb_ucfirst(trans('admin.title')),
        ]);
        $this->xPanel->addColumn([
            'name'          => 'active',
            'label'         => trans('admin.Active'),
            'type'          => "model_function",
            'function_name' => 'getActiveHtml',
            'on_display'    => 'checkbox',
        ]);

        // FIELDS

        $this->xPanel->addField([
            'name'       => 'title',
            'label'      => mb_ucfirst(trans('admin.title')),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => mb_ucfirst(trans('admin.title')),
            ],
        ]);

        $this->xPanel->addField([
            'name'   => 'picture',
            'label'  => trans('admin.Picture'),
            'type'   => 'image',
            'upload' => true,
            'disk'   => 'public',
        ]);

        $this->xPanel->addField([
            'name'  => 'active',
            'label' => trans('admin.Active'),
            'type'  => 'checkbox',
        ]);
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud();
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud();
    }
}

