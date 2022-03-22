<?php

namespace App\Http\Controllers;

use App\Models\memberRole;
use App\Models\departement;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class MemberRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('layouts.features.boards.board_memberRole')
        ->with('memberRoles',memberRole::orderBy('role_name')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Used to select the corresponding form view when you call the form for create
        $memberRole=[];
        $formView ='create';

        return view('layouts.features.forms.form_memberRole',compact([
            'formView'=>'formView',
            'memberRole'=>'memberRole',
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([ //input fields have not to be empty
            'role_name'=>['required','unique:member_roles','min:1'],
        ]);

        memberRole::create([
            'role_name'=>Str::ucfirst($request->role_name),
        ]);
        
        
        return redirect()->back()->with('success','created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\memberRole  $memberRole
     * @return \Illuminate\Http\Response
     */
    public function show(memberRole $memberRole)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\memberRole  $memberRole
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Used to select the corresponding form view when you call the form for edit
        $memberRole = memberRole::findOrfail($id);
        $formView ='edit';
        $cancelRoute = route('memberRole_all');

        $departements = departement::orderBy("dep_name")->get();
        $dep_id = $memberRole->dep_id;

        return view('layouts.features.forms.form_memberRole',
        compact([
            'memberRole'=>'memberRole',
            'formView'=>'formView',
            'cancelRoute'=>'cancelRoute'
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\memberRole  $memberRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([ //input fields have not to be empty
            'role_name'=>['required','min:1'],
        ]);

        $id = $request->memberRole_id;
        $memberRole = memberRole::where('memberRole_id',$id)->firstOrFail();

        $memberRole->update([
            'role_name'=>Str::ucfirst($request->role_name),
        ]);
        
        $route = route('memberRole_all');

        return redirect()->back()
        ->with('success','updated')
        ->with('route',$route);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\memberRole  $memberRole
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        memberRole::destroy($id);

        return redirect()->action([MemberRolesController::class,'index']);
    }
}
