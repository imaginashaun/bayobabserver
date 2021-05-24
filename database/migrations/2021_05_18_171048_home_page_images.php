<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HomePageImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        //
        Schema::create('home_page_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('picture', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->boolean('active')->unsigned()->nullable()->default('1');
            $table->timestamps();
            $table->index(["active"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
