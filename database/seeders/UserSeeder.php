<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_param = array(
            array('email' => 'admin@regonpkd.com', 'name' => 'admin', 'password' => 'password', 'nip' => '2222222222'),
        );
        foreach ($user_param as $row) {
            $user_data = User::where('email', $row['email'])->first();
            if (is_null($user_data)) {
                $userModel = new User();
                $userModel->name = $row['name'];
                $userModel->email = $row['email'];
                $userModel->password = Hash::make($row['password']);
                $userModel->nip = $row['nip'];
                $userModel->save();
                //assigning superadmin roles to this user by default
                $userModel->assignRole('admin');
            }
        }
        /**
         * End of creatign counsellor type user
         */
    }
}
