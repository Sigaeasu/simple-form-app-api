<?php

namespace App\Http\Controllers;
use App\Services\UserServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $userServices;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserServices $userServices)
        {
            $this->middleware('auth');
            $this->userServices = $userServices;
        }

    // Get all user data
    public function index()
        {
            
            $users = $this->userServices->fetchAll();

            if ($users)
                return $this->successRes($users, msgFetch(), 200);

            return $this->errorRes(msgNotFound('Users'), 404);

        }

    // Get one user data by ID
    public function show($id)
        {
            $user = $this->userServices->fetchById($id);

            if (!$user)
                return $this->errorRes(msgNotFound('User'), 404);

            return $this->successRes($user, msgFetch(), 200);
        }

    public function store(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:150',
                'username' => 'required|string|max:40',
                'password' => 'required|string|max:120',
                'email' => 'required|email|max:50',
                'birth_date' => 'required|date',
                'address' => 'required|string|max:120',
                'active' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return $this->errorRes($validator->getMessageBag()->toArray());
            }
            try {

                // Check for similar username
                $user = $this->userServices->fetchByUsername($request->username);

                if ($user)
                    return $this->errorRes(msgFound('Similar user'), 404);

                // Store User
                $store = $this->userServices->store($request);

                if($store){
                    return $this->successRes($store, msgStored());
                }else{
                    return $this->errorRes(msgNotStored());
                }
            } catch(\Exception $e){
                return $this->errorRes($e);
            }
        }

    public function update(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:150',
                'username' => 'required|string|max:40',
                'password' => 'required|string|max:120',
                'email' => 'required|email|max:50',
                'birth_date' => 'required|date',
                'address' => 'required|string|max:120',
                'active' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return $this->errorRes($validator->getMessageBag()->toArray());
            }
            try {

                // Check if user exist
                $user = $this->userServices->fetchById($request->user_id);

                if (!$user)
                    return $this->errorRes(msgNotFound('User'), 404);

                // Check for similar username
                $similar_user = $this->userServices->fetchByUsername($request->username);

                if (isset($similar_user) && $similar_user->id != $user->id)
                    return $this->errorRes(msgFound('Similar user'), 404);

                // Update
                $update = $this->userServices->update($user, $request);

                if($update){
                    return $this->successRes($update, msgUpdated());
                }else{
                    return $this->errorRes(msgNotUpdated());
                }
            } catch(\Exception $e){
                return $this->errorRes($e);
            }
        }

}
