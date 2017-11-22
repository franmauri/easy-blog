<?php

namespace App;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Laravel\Passport\HasApiTokens;
//use Laravel\Scout\Searchable;
use Spatie\Permission\Traits\HasRoles;
use Watson\Validating\ValidatingTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable {

    use Notifiable;

    use HasImage;
    use SoftDeletes;
    use HasRoles;
    use ValidatingTrait;

    //use Searchable;
    //be careful here so ad import doesnt die
    protected $rules = [
       
        'language' => 'required|in:en,es'
    ];
    protected $throwValidationExceptions = true;
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'name',
        'email',
        'password',
        'language'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getDefaultLangAttribute() {
        return $this->language ?: 'en';
    }

    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function avatar() {
        return $this->image();
    }

    public function setAvatar(Image $image) {
        $this->setImage($image);
    }

    public function validateForPassportPasswordGrant($password) {
        if (\Hash::check($password, $this->getAuthPassword())) {
            return true;
        }



        return false;
    }

    public function setPasswordAttribute($password) {
        return $this->attributes['password'] = bcrypt($password);
    }

    public static function make($data) {
        $user = new self;
        $user->rules = array_merge($user->rules, ['password' => 'required|string']);
        self::validate($data, $user);
        $user->fill($data);
        if ($image = array_get($data, 'image')) {
            $image = Image::create($image);
            $user->setAvatar($image);
        }

        if ($lang = array_get($data, 'language')) {
            $user->setSessionLang($lang);
        }


        if ($user->save()) {
            $user->syncRoles([array_get($data, 'role', ['user'])]);
            //$user->assignRole(array_get($data, 'role', 'user'));
            //$user->setPermissions(array_get($data, 'permissions', []), $user);
        }
        return $user;
    }

    public static function updateProfile(User $user, $data = []) {
        $newPassword = false; 
        if (!empty($data['password']) && !empty($data['oldPassword'])) {
            //if (password_verify($data['oldPassword'], $user->password)) {
            if (Hash::check($data['oldPassword'], $user->password)) {
                $newPassword = Hash::make($data['password']);
                $user->rules = array_merge($user->rules, ['password' => 'required|string', 'email' => 'required|email',
                    'name' => 'required|string']);
            } else {
                throw new \Exception('Old password is incorrect');
            }
        }
        $user->rules = array_merge($user->rules, ['email' => 'required|email','name' => 'required|string']);
        self::validate($data, $user);
        $user->fill($data);
        if ($image = array_get($data, 'image')) {
            $image = Image::create($image);
            $user->setAvatar($image);
        }





        if ($user->save())
            $user->syncRoles([array_get($data, 'role', ['user'])]);
        return $user;
    }

    private static function validate($data, $model) {


        $validator = Validator::make($data, $model->rules);

        if ($validator->fails()) {
            throw new ValidationException($validator, $model);
        }
    }

    public function toSearchableArray() {
        $array = $this->toArray();

        // Customize array...

        return $array;
    }

    public function scopeSearchByKeyword($query, $keyword) {
        if ($keyword != '') {
            $keyword = mb_strtolower($keyword);
            $query->where(function ($query) use ($keyword) {
                $query->whereRaw("BINARY LOWER(users.username) LIKE BINARY '%$keyword%'")->orWhereRaw("BINARY LOWER(users.email) LIKE BINARY '%$keyword%'");
            });
        }
        return $query;
    }

    public function scopeSortable($query) {
        return $query->orderBy('username', 'ASC')
                        ->orderBy('email', 'ASC');
    }

    public function setAuthenticatedUserSessionData() {
        $this->setSessionLang($this->default_lang);
    }

    public function setSessionLang($lang) {
        //set user lang
        session([
            'user_locale' => $lang,
        ]);

        //make the user lang the system lang
        \App::setLocale($lang);
    }

}
