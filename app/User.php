<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = [
//        'name', 'email', 'password',
//    ];
    protected $fillable = [
        'name', 'last_name', 'middle_name', 'birthday', 'inn', 'snils'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function org()
    {
        return $this->belongsTo('App\Organization', 'id');
    }

    public static function validateUserData($name, $last_name, $middle_name, $inn, $snils) {
        $result = [
            'user'   => [
                'name'          => '',
                'last_name'     => '',
                'middle_name'   => '',
                'inn'           => '',
                'snils'         => ''
            ],
            'errors'=> []
        ];

        if(strlen($name) >= 1 && strlen($name) <= 255) {
            $result['user']['name'] = true;
        }
        else {
            array_push($result['errors'], $last_name . ' ' . $name . ' ' . $middle_name . ' - Длина имени пользователя меньше 1 или больше 255 символов');
        }

        if(strlen($last_name) >= 1 && strlen($last_name) <= 255) {
            $result['user']['last_name'] = true;
        }
        else {
            array_push($result['errors'], $last_name . ' ' . $name . ' ' . $middle_name . ' - Длина фамилии пользователя меньше 1 или больше 255 символов');
        }

        if(strlen($middle_name) <= 255) {
            $result['user']['middle_name'] = true;
        }
        else {
            array_push($result['errors'], $last_name . ' ' . $name . ' ' . $middle_name . ' - Длина отчества пользователя больше 255 символов');
        }

        if (strlen($inn) == 16 && preg_match('/^[\d]+$/', $inn)) {
            $result['user']['inn'] = true;
        }
        else {
            array_push($result['errors'], $last_name . ' ' . $name . ' ' . $middle_name . ' - Длина ИНН не равна 16 символам или содержит буквенные символы');
        }

        if (strlen($snils) == 13 && preg_match('/^[\d]+$/', $snils)) {
            $result['user']['snils'] = true;
        }
        else {
            array_push($result['errors'], $last_name . ' ' . $name . ' ' . $middle_name . ' - Длина СНИЛС не равна 13 символам или содержит буквенные символы');
        }
        return $result;
    }
}
