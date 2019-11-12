<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request, $org) {
        $this->validate($request, [
            'name'=>'required|max:255|min:1',
            'last_name'=>'required|max:255|min:1',
            'middle_name'=>'required|max:255',
            'birthday'=>'required',
            'inn'=>'required|size:16|unique:users,inn|regex:/^[\d]+$/',
            'snils'=>'required|size:13|unique:users,snils|regex:/^[\d]+$/'
        ]);

        $user = new User();
        $user->name         = $request->name;
        $user->last_name    = $request->last_name;
        $user->middle_name  = $request->middle_name;
        $user->birthday     = $request->birthday;
        $user->inn          = $request->inn;
        $user->snils        = $request->snils;
        $user->org_id       = $org;

        if ($user->save()) {
            return redirect()->back()->with('success', 'Новый пользователь успешно добавлен.');
        }
        else {
            return redirect()->route('org.show', ['org' => $org])->with('error', 'Что-то пошло не так при удалении пользователя.');
        }


    }

    public function show($id) {
        $user = User::find($id);
        return view('user.show', compact('user'));
    }

    public function destroy($id) {
        $user = User::find($id);

        $success = 'Удаление "' . $user->last_name . ' ' . $user->name . ' ' . $user->middle_name . '" произведено успешно.';

        if ($user->delete()) {
            return redirect()->route('org.show', ['org' => $user->org_id])->with('success', $success);
        }
        else {
            return redirect()->route('user.show', ['user' => $id])->with('error', 'Что-то пошло не так при удалении пользователя.');
        }
    }
}
