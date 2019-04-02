<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;


class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@hyuna.bb',
            'password' => Hash::make('123123')
        ]);

        $user = User::whereName('Admin')->first();

        Role::create(['name' => 'admin']);
        $user->assignRole('admin');
    }
}
