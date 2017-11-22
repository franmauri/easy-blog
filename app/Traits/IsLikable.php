<?php

namespace App\Traits;

use App\User;
use Illuminate\Support\Facades\Validator;
use ReflectionClass;
use Watson\Validating\ValidationException;

trait IsLikable {

    public function likes() {
        $reflect = new ReflectionClass($this);
        $modelKey = strtolower($reflect->getShortName());

        return $this->belongsToMany(User::class, $modelKey . '_likes', $modelKey . '_id', 'user_id')
                        ->withTimestamps()
                        ->withPivot('created_at')
                ->withPivot('liked')
                        ->orderBy('created_at', 'desc');
    }

    /**
     * 
     * @param type $userOrId
     * @param type $like 1|0
     * @return $this
     * @throws ValidationException
     */
    public function like($userId, $liked) {
        $validator = Validator::make(['user_id' => $userId], [
                    'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator, $this);
        }
        $likes = $this->likes->where('id',$userId)->first();
        if ($likes && $likes->pivot->liked == $liked) 
            return;
        $this->likes()->detach($userId);
        $this->likes()->attach($userId, ['liked' => $liked]);
        $this->no_of_likes = $liked ? $this->no_of_likes + 1 : $this->no_of_likes - 1;
        $this->load('likes');
        $this->save();
        return $this;
    }

}
