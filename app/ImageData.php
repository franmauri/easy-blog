<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ImageData
 *
 * @property-read \App\Image $user
 * @mixin \Eloquent
 */
class ImageData extends Model
{

    public $timestamps = false;

    protected $fillable = ['small', 'medium', 'large', 'original', 'image_id'];

    public function user()
    {
        return $this->belongsTo(Image::class);
    }
}
