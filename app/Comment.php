<?php

namespace App;

use App\Traits\HasImage;
use App\Traits\IsCommentable;
use App\Traits\IsLikable;
use App\Transformers\CommentTransformer;
use Flugg\Responder\Contracts\Transformable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Watson\Validating\ValidatingTrait;
use Watson\Validating\ValidationException;

/**
 * App\Comment
 *
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @mixin \Eloquent
 */
class Comment extends \Baum\Node implements Transformable
{
    use HasImage, IsLikable, IsCommentable;
    use ValidatingTrait;
    use SoftDeletes;

    protected $rules = [
        'text' => 'required',
        'user_id' => 'required'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $throwValidationExceptions = true;

    protected $fillable = [
        'text', 'user_id'
    ];

    protected $with = ['comments'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The transformer used to transform the model data.
     **/
    public function transformer()
    {
        return CommentTransformer::class;
    }

    public static function make(array $data, $userOrId)
    {
        $comment = new self;

        $data = array_merge($data, [
            'user_id' => get_object_id(User::class, $userOrId),
        ]);

        self::validateData($userOrId, $data, $comment);


        $comment->fill(array_except($data,'image'));

        if($image = array_get($data, 'image')){
            $image = Image::create($image);
            $comment->setImage($image);
        }

        return $comment;
    }

    private static function validateData($userOrId, $data, $comment)
    {
        $data = array_merge(['user_id' => $userOrId], $data);
        $validator = Validator::make($data, [
            'user_id' => 'required',
            'text' => 'required_without:image',
            'image' => 'required_without:text',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator, $comment);
        }
    }
}
