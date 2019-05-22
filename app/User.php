<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    // protected $guard = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nik', 'name', 'password', 'id_role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function permission($id_user, $field) {
        $data = \DB::select("
            SELECT
                b.$field AS field
            FROM users AS a
            LEFT JOIN authorizations AS b ON a.id_role = b.id 
        ");
        return $data[0]->field;
    }

    public static function registeruser($input = array()) {
        return User::create([
            'nik' => $input['nik'],
            'name' => $input['name'],
            'password' => bcrypt($input['password']),
        ]);
    }

    // JWT method

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
