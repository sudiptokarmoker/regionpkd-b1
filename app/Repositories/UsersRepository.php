<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersRepository implements UsersInterface
{
    public function all()
    {
        return User::orderBy('created_at', 'desc')->get();
    }
    /**
     * find or fail method
     */
    public function findOrFail($id)
    {
        return User::find($id);
    }
    /**
     * find by id
     */
    public function find($id)
    {
        return User::findOrFail($id);
    }
    /**
     * save method
     */
    public function updateUser($request)
    {
        $userModel = User::findOrFail($request['id']);
        /**
         * update password and name
         */
        $userModel->name = $request['name'];
        if ($request['password']) {
            $userModel->password = Hash::make($request['password']);
        }
        $userModel->save();
        return true;
    }
}
