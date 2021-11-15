<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserServices
{

    // index
    public function fetchAll()
    {
        return User::get();
    }

    // show user by id
    public function fetchById($id)
    {
        return User::whereId($id)->first();
    }

    // show user by username
    public function fetchByUsername($username)
    {
        return User::where('username', $username)->first();
    }

    // store user
    public function store($data)
    {
        try {
            $user = User::create([
                'name' => $data->name,
                'username' => $data->username,
                'password' => Hash::make($data->password),
                'email' => $data->email,
                'birth_date' => Carbon::parse($data->birth_date),
                'address' => $data->address,
                'active' => $data->active,
            ]);

            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }

    // update user
    public function update($user, $data)
    {
        try {

            $user->username = $data->username;
            $user->name = $data->name;

            if ($data->password != null && $data->password != '')
                $user->password = Hash::make($data->password);

            $user->email = $data->email;
            $user->birth_date = Carbon::parse($data->birth_date);
            $user->address = $data->address;
            $user->active = $data->active;

            $user->save();

            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }

}