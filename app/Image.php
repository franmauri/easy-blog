<?php

namespace App;

use App\Exceptions\ImageNotValidException;
use App\Traits\IsCommentable;
use App\Traits\IsLikable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Image extends Model
{
  use SoftDeletes;


  protected $dates = ['created_at', 'updated_at', 'deleted_at'];

  protected $fillable = ['mime', 'filename', 'description', 'image_data_id', 'name'];

  private static $dimensions = [
    'small' => ['width' => 80, 'height' => 80],
    'medium' => ['width' => 300, 'height' => 200],
    'large' => ['width' => 600, 'height' => 400],
  ];

  public function toSearchableArray()
  {
    $array = $this->toArray();
    return array_only($array, ['name', 'description']);
  }

  public function data()
  {
    return $this->hasOne(ImageData::class);
  }

  public function galleries()
  {
    return $this->belongsToMany(Gallery::class)
      ->withPivot('cover')
      ->withTimestamps()
      ->orderBy('created_at', 'desc');
  }

  public function serve($size = 'original')
  {
    $imageDecoded = base64_decode(str_replace('data:' . $this->mime . ';base64,', '', $this->getSize($size)));
    $response = \Response::make($imageDecoded, 200);
    $response->header('Content-Type', $this->mime);
    return $response;
  }

  public function url($size = 'original')
  {
    return route('image', ['id' => $this->id, 'size' => $size]);
  }

  public function base64($size = 'original')
  {
    return $this->getSize($size);
  }

  private function getSize($size)
  {
    return $this->data->attributes[$size];
  }

  public static function create($file, $description = null)
  {
    $fileName = 'dummy.jpg';
    if (gettype($file) == 'object') {
      if (!$file->isValid())
        throw new ImageNotValidException;
      $fileName = $file->getClientOriginalName();
    }

    $original = \ImageIntervention::make($file);

    $image = new self([
      'mime' => $original->mime(),
      'filename' => $fileName,
      'description' => $description,
      'name' => substr($fileName, 0, (strrpos($fileName, ".")))
    ]);

    $image->save();

    $image->data()->create([
      'small' => self::resize($original, 'small'),
      'medium' => self::resize($original, 'medium'),
      'large' => self::resize($original, 'large'),
      'original' => self::resize($original, 'original'),
    ]);

    return $image;
  }

  private static function resize($image, $size = 'original')
  {
    $dimensions = config('image.dimensions');

    $height = array_get($dimensions, $size . '.height', array_get(self::$dimensions, $size . '.height', $image->height()));
    $width = array_get($dimensions, $size . '.width', array_get(self::$dimensions, $size . '.width', $image->width()));

    $image = clone $image;

    if ($image->height() > $image->width() && $height != $width) {
      $image = $image->resize($width, $height, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      });
      $image->resizeCanvas($width, $height, 'center', false, '#dadada');
    } else {
      $image = $image->fit($width, $height, function ($constraint) {
        $constraint->upsize();
      });
    }


    return (string)$image->encode('data-url');
  }

}
