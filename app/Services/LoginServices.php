<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Exception;

class LoginServices
{
    // login
    public function login($request) 
    {    
        try {    
            $username = $request->username;
            $password = $request->password;

            $user = User::where('username', $username)->first();

            if ($user) {
                if (Hash::check($password, $user->password)) {
                    $token = JWTAuth::fromUser($user);
                    return ['user' => $user, 'token' => $token];
                }
            }

            return false;
        } catch (Exception $e) {
            throw $e;
        }
    }
}