<?php

namespace App\Http\Controllers\Users;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;

/**
 * Class UsersController
 * @package App\Http\Controllers\Users
 */
class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $users = $user->orderBy('username')->paginate(20);

        return view('users/index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {

        $roles = Role::all();
        
        return view('users/create', [
            'fields' => [
                'name', 'username', 'email', 'password',
            ],
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param User $user
     * @param  StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request, User $user)
    {
        $user->name = $request->get('name');
        $user->username = $request->get('username');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));

        $result = $user->save();

        if ($request->has('roles')) {
            $user->assignRole($request->get('roles'));
        }

        if (!$result) {
            return redirect(route('users.index'))->with('error', 'Something went wrong. User wasn\'t created.');
        }

        return redirect(route('users.index'))->with('success', 'User successfully created');
    }

    /**
     * Change the user password
     */
    public function password($id, Request $request) {
        $result = User::where('id', $id)
            ->limit(1)
            ->update(['password' => Hash::make($request->get('password'))]);

        if ($result > 0) {
            return redirect()->back()->with('success', 'Password successfully changed.');
        }

        return redirect()->back()->with('error', 'Failed to change password');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::with('roles')->find($id);

        if (!$user) {
            return redirect(route('users.index'))->with('error', "Cannot find user with ID: $id");
        }

        $roles = Role::all();

        if (!$roles) {
            session()->flash('error', 'Failed to fetch roles');
        }

        return view('users.edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->back()->with('error', 'Cannot update user info.');
        }

        $user->name = $request->get('name');
        $user->username = $request->get('username');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));

        try {
            $result = $user->save();
        } catch (\Exception $e) {
            $result = false;

            if ($e->getCode() == 23000) {
                return redirect()->back()->with('error', "email {$request->get('email')} already taken.");
            }
        }

        if ($request->has('roles')) {
            $user->syncRoles($request->get('roles'));
        }

        if (!$result) {
            return redirect(route('users.index'))->with('error', 'Something went wrong. User info wasn\'t updated.');
        }

        return redirect(route('users.index'))->with('success', 'User info successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = User::destroy($id);

        if ($result) {
            return redirect()->back()->with('success', "User successfully deleted (ID: $id)");
        }
    }
}
