<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {

//        $user = User::create([
//            'name' => 'ALireza',
//            'email' => 'Alireza@gmail.com',
//            'password' => bcrypt('123456')
//        ]);
//        $role = Role::find(1);
//        $user->roles()->attach($role);
//        $this->authorize('show-users');
        $users = User::latest()->paginate(25);
        return view('Admin.users.all', compact('users'));
    }

    public function destroy(User $user)
    {

        $user->delete();
        return back();
    }

}
