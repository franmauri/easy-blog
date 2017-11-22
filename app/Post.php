<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Watson\Validating\ValidatingTrait;
use Watson\Validating\ValidationException;
//use Laravel\Scout\Searchable;
use App\Traits\HasImage;
use App\Traits\IsCommentable;
use App\Traits\IsLikable;

class Post extends Model {

    use ValidatingTrait,
        SoftDeletes,
        IsCommentable,
        HasImage,
        IsLikable;

    protected $rules = [
        'user_id' => 'required|integer',
        'title' => 'required|string',
        'content' => 'required|string',
    ];
    protected $throwValidationExceptions = true;
    protected $fillable = ['user_id', 'title', 'content'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getExcerptAttribute() {
        return getExcerpt($this->content_lang);
    }

    public function getContentLangAttribute() {
        $content = json_decode($this->content, true);
        return isset($content[getUserLang()]) ? $content[getUserLang()] : $content[getOpositeUserLang()];
    }

    public function getTitleLangAttribute() {
        $title = json_decode($this->title, true);
        return isset($title[getUserLang()]) ? $title[getUserLang()] : $title[getOpositeUserLang()];
    }

    public function getCurrentUserLikeAttribute() {
        if (\Auth::check()) {
            $likes = $this->likes->where('id', \Auth::user()->id)->first();

            if ($likes) {
                return $likes->pivot->liked == 1 ? 'up' : 'down';
            }
        }
        return false;
    }

    public static function make($data, User $user) {
        $post = new self;
        $attrs = [
            'user_id' => $user->id,
            'title' => collect($data['form'])->pluck('title', 'lang')->toJson(),
            'content' => collect($data['form'])->pluck('content', 'lang')->toJson(),
        ];

        $langs = collect($data['form'])->pluck('lang')->toArray();
        self::validate($langs, $post, $attrs);
        $post = $post->fill($attrs);
        $post->save();
        if ($image = array_get($data, 'image')) {
            $post->setPhoto($image);
        }
        return $post;
    }

    public static function updatePost(Post $post, $data) {
        $attrs = [
            'title' => collect($data['form'])->pluck('title', 'lang')->toJson(),
            'content' => collect($data['form'])->pluck('content', 'lang')->toJson(),
        ];
        $langs = collect($data['form'])->pluck('lang')->toArray();
        unset($post->rules['user_id']);
        self::validate($langs, $post, $attrs);
        $post = $post->fill($attrs);
        $post->save();
        if ($image = array_get($data, 'image')) {
            $post->setPhoto($image);
        }
        return $post;
    }

    private static function validate($langs, $post, $attrs) {
        $validator = Validator::make($attrs, $post->rules);
        if ($validator->fails() || !array_intersect($langs, ['en', 'es'])) {
            throw new ValidationException($validator, $post);
        }
    }

    public function scopeSearchByKeyword($query, $keyword) {
        if ($keyword != '') {
            $query->where(function ($query) use ($keyword) {
                $query->whereRaw("BINARY LOWER(title) LIKE BINARY '%" . mb_strtolower($keyword) . "%'")
                        ->orWhereRaw("BINARY LOWER(content) LIKE BINARY '%" . mb_strtolower($keyword) . "%'");
            });
        }
        return $query;
    }

}
