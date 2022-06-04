<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            "username" => "required",
            "email" => "required",
            "password" => "required_with:password_confirmation|same:password_confirmation",
            'password_confirmation' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $err = array(
                "username" => $errors->first("username"),
                "email" => $errors->first("email"),
                "password" => $errors->first("password"),
                "password_confirmation" => $errors->first("password_confirmation"),
            );
            return response()
                ->json(array(
                    "message" => "Can't process request. Check your input",
                    "errors" => $err
                ), 422);
        }

        $user = new User;
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input("password"));
        $user->save();

        return response()->json(array(
            "message" => "Registration Successful",
            "user" => $user
        ), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
