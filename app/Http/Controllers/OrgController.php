<?php

namespace App\Http\Controllers;

use App\Organization;
use Illuminate\Http\Request;

class OrgController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orgs = Organization::all();
        return view('org.index', compact('orgs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('org.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'display_name'  => 'required|max:255|min:1',
            'ogrn'          => 'required|size:13|unique:organizations,ogrn|regex:/^[\d]+$/',
            'oktmo'         => 'required|size:11|unique:organizations,oktmo|regex:/^[\d]+$/'
        ]);

        $org = new Organization();
        $org->display_name  = $request->display_name;
        $org->ogrn          = $request->ogrn;
        $org->oktmo         = $request->oktmo;

        if($org->save()) {
            return redirect()->route('org.show', $org);
        }
        else {
            return redirect()->route('org.create')->with('error', 'Что-то пошло не так при создании новой организации.');
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $org = Organization::find($id);

        return view('org.show', compact('org'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $org = Organization::find($id);

        $success = 'Удаление "' . $org->display_name . '" и данных ' . $org->users()->count() . ' пользователей произведено успешно.';

        if ($org->users()->delete() && $org->delete()) {
            return redirect()->route('org.index')->with('success', $success);
        }
        else {
            return redirect()->route('org.show', ['org' => $id])->with('error', 'Что-то пошло не так при удалении ораганизации.');
        }
    }
}
