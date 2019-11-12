<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    public $timestamps = false;

    public function users() {
        return $this->hasMany('App\User', 'org_id');
    }

    public static function validateOrgData($name, $ogrn, $oktmo) {
        $result = [
            'org'   => [
                'name' => '',
                'ogrn' => '',
                'oktmo'=> ''
            ],
            'errors'=> []
        ];

        if(strlen($name) >= 5 && strlen($name) <= 255) {
            $result['org']['name'] = true;
        }
        else {
            array_push($result['errors'], $name . ' - Длина названия организации меньше 5 или больше 255 символов');
        }

        if (strlen($ogrn) == 13 && preg_match('/^[\d]+$/', $ogrn)) {
            $result['org']['ogrn'] = true;
        }
        else {
            array_push($result['errors'], $name . ' - Длина ОГРН не равна 13 символам или содержит буквенные символы');
        }

        if (strlen($oktmo) == 11 && preg_match('/^[\d]+$/', $oktmo)) {
            $result['org']['oktmo'] = true;
        }
        else {
            array_push($result['errors'], $name . ' - Длина ОКТМО не равна 11 символам или содержит буквенные символы');
        }
        return $result;
    }
}
