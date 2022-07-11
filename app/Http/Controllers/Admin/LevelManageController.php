<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class LevelManageController extends Controller
{
    public function index()
    {
        $roles = Role::latest()->with('users')->paginate(20);
        return view('Admin.levelAdmins.all' , compact('roles'));
    }

    public function create()
    {
        return view('Admin.levelAdmins.create');
    }

    public function store(Request $request)
    {
        $this->validate($request , [
            'user_id' => 'required',
            'role_id' => 'required'
        ]);

        User::find($request->input('user_id'))->roles()->sync($request->input('role_id'));
        return redirect(route('level.index'));

    }

    public function edit(User $user)
    {
        return view('Admin.levelAdmins.edit' , compact('user'));
    }

    public function update(Request $request , User $user)
    {
        $user->roles()->sync($request->input('role_id'));
        return redirect(route('level.index'));
    }

    public function destroy(User $user)
    {
        $user->roles()->detach();
        return redirect(route('level.index'));
    }
}
