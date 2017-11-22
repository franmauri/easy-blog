<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename');
            $table->string('name')->nullable();
            $table->string('mime');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('image_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('image_id')->unsigned();
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
        });

        \DB::statement("ALTER TABLE image_datas ADD original LONGBLOB");
        \DB::statement("ALTER TABLE image_datas ADD large LONGBLOB");
        \DB::statement("ALTER TABLE image_datas ADD medium LONGBLOB");
        \DB::statement("ALTER TABLE image_datas ADD small LONGBLOB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_datas');
        Schema::dropIfExists('images');
    }
}