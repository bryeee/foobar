<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * TODO: extract validation to form request and business logic to service class if needed.
     * 
     * User registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // validate request 
        $validated = $this->validate($request, [
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // encrypt password
        data_set($validated, 'password', bcrypt($request->password));

        // create user
        User::create($validated);

        // response json
        return response()->json(['message' => 'User successfully registered.'], Response::HTTP_CREATED);
    }

    /**
     * TODO: extract validation to form request and business logic to service class if needed.
     * 
     * User login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
    	// validate request
        $validated = $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        // validate credentials
        if (! $token = auth()->attempt($validated)) {
            return response()->json(['message' => 'Unauthorized.'], Response::HTTP_UNAUTHORIZED);
        }
        
        // response json
        return response()->json(['data' => $this->createToken($token)]);
    }

    /**
     * Logout user.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully logged out.']);
    }

    /**
     * TODO: extract business logic to service class if needed.
     * 
     * Token creation.
     *
     * @param String $token
     * @return Array
     */
    protected function createToken(String $token): Array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ];
    }
}
