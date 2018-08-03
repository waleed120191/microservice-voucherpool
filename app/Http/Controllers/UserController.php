<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;



class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function all()
    {
        // TODO: Pagination can be ued for optimized result
        $users = \App\User::all();
        return response()->json(['status'=>1,'data'=>$users],200);
    }

    public function existByEmail()
    {
        $input = Input::get();

        $validator = \Validator::make($input, [
            'email' => 'required|email|exists:mysql.users,email'
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>0,'message'=>$validator->errors()],200);
        }else{
            return response()->json(['status'=>1,'message'=>'exist'],200);
        }

    }
}
