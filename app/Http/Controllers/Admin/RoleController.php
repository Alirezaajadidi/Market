<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use function React\Promise\reduce;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::latest()->paginate();
        return view('Admin.roles.all', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'permission_id' => 'required',
            'name' => 'required',
            'label' => 'required'
        ]);
        $role = Role::create($request->all());
        $role->permissions()->attach($request->input('permission_id'));
        return redirect(route('roles.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        return view('Admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'permission_id' => 'required',
            'name' => 'required',
            'label' => 'required'
        ]);

        $role->update($request->all());
        $role->permissions()->sync($request->input('permission_id'));
        return redirect(route('roles.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return back();
    }
}
