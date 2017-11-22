<?php

namespace App\Traits;

use App\Image;

trait HasImage {

    public function image() {
        return $this->belongsTo('App\Image', 'image_id');
    }

    public function setImage(Image $image) {
        $this->image()->associate($image);
        return $this;
    }

    public function setPhoto($photo) {
        $image = Image::create($photo);
        $this->setImage($image);
        $this->save();
        return $this;
    }

    public function getImage($size = 'original', $type = 'url') {
        if (!$this->image)
            return null;

        if ($type == 'base64') {
            return $this->image->base64($size);
        } else {
            return $this->image->url($size);
        }
    }

}
