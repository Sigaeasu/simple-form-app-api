<?php

namespace App\Http\Controllers;

use App\Services\UserServices;
use App\Services\LoginServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    private $userServices, $loginServices;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserServices $userServices, LoginServices $loginServices)
        {
            $this->userServices = $userServices;
            $this->loginServices = $loginServices;
        }

    // Auth
    public function login(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:40',
                'password' => 'required|string|max:120'
            ]);

            if ($validator->fails()) {
                return $this->errorRes($validator->getMessageBag()->toArray());
            }

            $user = $this->userServices->fetchByUsername($request->username);

            if (!$user) 
                return $this->errorRes(msgNotFound('Users'), 404);

            $login = $this->loginServices->login($request);

            if (!$login) 
                return $this->errorRes(msgNotFound('Login'), 404);
            
            return $this->successRes($login, msgFetch(), 200);
            
        }   
}