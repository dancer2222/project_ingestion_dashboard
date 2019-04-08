<?php

namespace App\Http\Controllers\Roles;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('roles.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Permission $permission)
    {
        $validate = $request->validate([
            'name' => 'required|unique:ingestion.permissions,name',
        ]);

        $permission->name = $request->get('name');
        $result = $permission->save();

        if (!$result) {
            return redirect(route('roles.index'))->with('error', 'Something went wrong. Permission wasn\'t created.');
        }

        return redirect(route('roles.index'))->with('success', 'Permission successfully created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return redirect(route('roles.index'))->with('error', "Cannot find permission with ID: $id");
        }

        return view('roles.permissions.edit', [
            'permission' => $permission,
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
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->back()->with('error', 'Cannot find permission.');
        }

        if ($permission->name !== $request->get('name')) {
            $request->validate([
                'name' => 'required|unique:ingestion.permissions,name',
            ]);

            $permission->name = $request->get('name');

            try {
                $result = $permission->save();
            } catch (\Exception $e) {
                $result = false;
            }
        }

        if (!$result) {
            return redirect(route('roles.index'))->with('error', 'Something went wrong. Permission wasn\'t updated.');
        }

        return redirect(route('roles.index'))->with('success', 'Permission successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = Permission::destroy($id);

        if ($result) {
            return redirect()->back()->with('success', "Permission successfully deleted (ID: $id)");
        }

        return redirect()->back()->with('error', "Something wrong. Can't delete permission (ID: $id)");
    }
}

