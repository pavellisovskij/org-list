<?php

namespace App\Http\Controllers;

use App\Organization;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class XMLController extends Controller
{
    public function upload(Request $request)
    {
        /* Блок проверок */
        $this->validate($request, [
            'xml_file' => 'mimes:xml|file|required'
        ]);

        $xml = new \SimpleXMLElement(file_get_contents($request->file('xml_file')));
        $query_result = [];
        $org_errors = [];
        $users_errors = [];

        foreach ($xml->org as $new_org) {
            $org_result = Organization::validateOrgData($new_org->attributes()->displayName, $new_org->attributes()->ogrn, $new_org->attributes()->oktmo);

            if ($org_result['errors'] != []) {
                array_push($org_errors, $org_result['errors']);
            }

            if ($org_result['org']['name'] == true && $org_result['org']['ogrn'] == true && $org_result['org']['oktmo'] == true) {
                $data = Organization::where([
                    'ogrn' => $new_org->attributes()->ogrn,
                    'oktmo'=> $new_org->attributes()->oktmo
                ])->first();

                if (!isset($data)) {
                    $org = new Organization();
                    $org->display_name  = $new_org->attributes()->displayName;
                    $org->ogrn          = $new_org->attributes()->ogrn;
                    $org->oktmo         = $new_org->attributes()->oktmo;
                    $org->save();

                    $org_id = $org->id;
                }
                else {
                    $org_id = $data['id'];

                    array_push($query_result, 'Организация с названием ' . $new_org->attributes()->displayName . ' уже существует. Запись пропущена.');
                }

                foreach ($new_org->user as $new_user) {
                    $user_result = User::validateUserData(
                        $new_user->attributes()->firstname,
                        $new_user->attributes()->lastname,
                        $new_user->attributes()->middlename,
                        $new_user->attributes()->inn,
                        $new_user->attributes()->snils
                    );

                    if ($user_result['errors'] != []) {
                        array_push($users_errors, $user_result['errors']);
                    }

                    if ($user_result['user']['name'] == true &&
                        $user_result['user']['last_name'] == true &&
                        $user_result['user']['middle_name'] == true &&
                        $user_result['user']['inn'] == true &&
                        $user_result['user']['snils'] == true
                    ) {
                        $user_data = User::where([
                            'snils' => $new_user->attributes()->snils,
                            'org_id'=> $org_id
                        ])->first();

                        if (!isset($user_data)) {
                            $user = new User();
                            $user->name         = $new_user->attributes()->firstname;
                            $user->last_name    = $new_user->attributes()->lastname;
                            $user->middle_name  = $new_user->attributes()->middlename;
                            $user->birthday     = \Carbon\Carbon::parse($new_user->attributes()->birthday)->format('Y-m-d');
                            $user->inn          = $new_user->attributes()->inn;
                            $user->snils        = $new_user->attributes()->snils;
                            $user->org_id       = $org_id;
                            $user->save();
                        }
                        else {
                            array_push($query_result, 'Пользователь ' . $new_user->attributes()->firstname . ' ' . $new_user->attributes()->lastname . ' ' . $new_user->attributes()->middlename . ' уже существует. Запись пропущена.');
                        }
                    }
                }
            }
        }

        if ($org_errors || $users_errors || $query_result) {
            if (isset($org_errors)) {
                $err=[];
                foreach ($org_errors as $org) {
                    foreach ($org as $error) {
                        array_push($err, $error);
                    }
                }
                $err_message = implode('|', $err);
                if ($err_message != "") {
                    Session::flash('org_errors', $err_message);
                }
            }
            if(isset($users_errors)) {
                $err=[];
                foreach ($users_errors as $user) {
                    foreach ($user as $error) {
                        array_push($err, $error);
                    }
                }
                $err_message = implode('|', $err);
                if ($err_message != "") {
                    Session::flash('user_errors', $err_message);
                }
            }
            if (isset($query_result)) {
                Session::flash('query_result', $query_result);
            }
            return redirect()->route('org.index')->with('success', 'Файл проанализирован, имеются исключения загрузки данных.');
        }
        else {
            return redirect()->route('org.index')->with('success', 'Загрузка из файла проиведена успешно.');
        }
    }
}