<?php

namespace App\Http\Controllers\Roles;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        $perms = Permission::all();

        return view('roles.index', [
            'roles' => $roles,
            'permissions' => $perms,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $perm = Permission::all();

        return view('roles.create', [
            'permissions' => $perm,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Role $role)
    {
        $validate = $request->validate([
            'name' => 'required|unique:ingestion.roles,name',
        ]);

        $role->name = $request->get('name');
        $result = $role->save();

        if ($request->has('permissions')) {
            $role->givePermissionTo($request->get('permissions'));
        }

        if (!$result) {
            return redirect(route('roles.index'))->with('error', 'Something went wrong. Role wasn\'t created.');
        }

        return redirect(route('roles.index'))->with('success', 'Role successfully created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::with('permissions')->find($id);

        if (!$role) {
            return redirect(route('roles.index'))->with('error', "Cannot find role with ID: $id");
        }

        $perms = Permission::all();

        if (!$perms) {
            session()->flash('error', 'Failed to fetch permissions');
        }

        return view('roles.edit', [
            'role' => $role,
            'permissions' => $perms,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = true;
        $role = Role::find($id);

        if (!$role) {
            return response()->back()->with('error', 'Cannot find role.');
        }

        if ($role->name !== $request->get('name')) {
            $request->validate([
                'name' => 'required|unique:ingestion.roles,name',
            ]);

            $role->name = $request->get('name');

            try {
                $result = $role->save();
            } catch (\Exception $e) {
                $result = false;
            }
        }

        if ($request->has('permissions')) {
            // die(var_dump($request->get('permissions')));
            $role->syncPermissions($request->get('permissions'));
        }

        if (!$result) {
            return redirect(route('roles.index'))->with('error', 'Something went wrong. Role wasn\'t updated.');
        }

        return redirect(route('roles.index'))->with('success', 'Role successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = Role::destroy($id);

        if ($result) {
            return redirect()->back()->with('success', "Role successfully deleted (ID: $id)");
        }
    }
}
