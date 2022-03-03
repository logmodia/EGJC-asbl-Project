<?php

namespace App\Http\Controllers;

use App\Models\weektopic;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WeektopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('layouts.features.boards.board_weektopic')
        ->with('weektopics',weektopic::orderBy('weekdate','desc')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Used to select the corresponding form view when you call the form for create
        $weektopic=[];
        $formView ='create';
        return view('layouts.features.forms.form_weektopic',compact(['formView'=>'formView', 'weektopic'=>'weektopic']));
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
            'topic'=>['required','max:80','min:4'], // has min 4 max 4 characters
            'verse'=>['required','max:80','min:5'],
            'weekdate'=>['required'],
        ]);

        /* $validatedData = $request->validate([
            'topic' => ['required', 'unique:posts', 'max:255'],
            'verse' => ['required'],
        ]); */

        weektopic::create([
            'topic'=>Str::upper($request->topic),
            'verse'=>Str::ucfirst($request->verse),
            'weekdate'=>$request->weekdate,
        ]);
        
        
        return redirect()->back()->with('success','created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\weektopic  $weektopic
     * @return \Illuminate\Http\Response
     */
    public function show(weektopic $weektopic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\weektopic  $weektopic
     * @return \Illuminate\Http\Response
     */
    public function edit($id = Null)
    {
        //Used to select the corresponding form view when you call the form for edit
        $weektopic = weektopic::findOrfail($id);
        $formView ='edit';
        $cancelRoute = route('weektopic_all');

        return view('layouts.features.forms.form_weektopic',
        compact([
            'weektopic'=>'weektopic',
            'formView'=>'formView',
            'cancelRoute'=>'cancelRoute'
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\weektopic  $weektopic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, weektopic $weektopic)
    {
        $request->validate([ //input fields have not to be empty
            'topic'=>['required','max:80','min:4'], // has min 4 max 4 characters
            'verse'=>['required','max:80','min:5'],
            'weekdate'=>['required'],
        ]);

        $id = $request->id;
        $weektopic = weektopic::where('id',$id)->firstOrFail();

        $weektopic->update([
            'topic'=>Str::upper($request->topic),
            'verse'=>Str::ucfirst($request->verse),
            'weekdate'=>$request->weekdate,
        ]);

        $route = route('weektopic_all');

        return redirect()->back()
        ->with('success','updated')
        ->with('route',$route);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\weektopic  $weektopic
     * @return \Illuminate\Http\Response
     */
    public function destroy(request $request,$id)
    {
        weektopic::destroy($id);

        return redirect()->action([WeektopicController::class,'index']);
    }
    
}
