<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    //Register API (POST, formdata)
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        //Data Save
        User::create([
           'name' => $request->name,
           'email' => $request->email,
           'password' => Hash::make($request->password),
        ]);

        //Response
        return response()->json([
            'status' => true,
            'message' =>'User created successfully'
        ], 201);
    }

    //Login API (POST, formdata)
    public function login(Request $request)
    {
        //data validation
        $validator = Validator::make($request->all(),[
           'email' => 'required|email',
           'password' => 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        //JWRAuth and attempt
        $token = JWTAuth::attempt([
           'email' => $request->email,
           'password' => $request->password
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User Logged successfully',
            'token' => $token
        ]);
    }

    //Profile API (GET)
    public function profile()
    {
        $userData = Auth::user();

        return response()->json([
            'status' => true,
            'message' => 'profile data'
        ]);

    }

    //Refresh Token API (GET)
    public function refreshToken()
    {

    }

    //Logout API (GET)
    public function logout()
    {

    }

}
