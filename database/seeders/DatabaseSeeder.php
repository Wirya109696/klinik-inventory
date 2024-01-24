<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

    // Karyawan::create([
    //     'name' => 'Wirya',
    //     'email' => 'wirya109@gmail.com',
    //     'alamat' => 'Morowali'
    // ]);

    // Karyawan::factory(3)->create();
      // create permissions
    Permission::create([
        'name'       => 'configuration-index',
        'menu_name'  => 'Configuration',
        'parent_id'  => 0,
        'icon'       => 'bi bi-gear-wide-connected',
        'route_name' => '/configuration/users',
        'has_child'  => 'Y',
        'has_route'  => 'N',
        'order_line' => '10',
        'guard_name' => 'web'
    ]);

    Permission::create([
        'name'       => 'permissions-index',
        'menu_name'  => 'Permissions',
        'parent_id'  => 1,
        'icon'       => 'bi bi-bezier2',
        'route_name' => '/configuration/permissions',
        'has_child'  => 'N',
        'has_route'  => 'Y',
        'order_line' => '10.3',
        'guard_name' => 'web'
    ]);

    Permission::create([
        'name'       => 'permissions-store',
        'menu_name'  => 'Permissions',
        'parent_id'  => 1,
        'icon'       => 'bi bi-bezier2',
        'route_name' => '/configuration/permissions',
        'has_child'  => 'N',
        'has_route'  => 'Y',
        'order_line' => '10.3',
        'guard_name' => 'web'
    ]);

    Permission::create([
        'name'       => 'permissions-edit',
        'menu_name'  => 'Permissions',
        'parent_id'  => 1,
        'icon'       => 'bi bi-bezier2',
        'route_name' => '/configuration/permissions',
        'has_child'  => 'N',
        'has_route'  => 'Y',
        'order_line' => '10.3',
        'guard_name' => 'web'
    ]);

    Permission::create([
        'name'       => 'permissions-erase',
        'menu_name'  => 'Permissions',
        'parent_id'  => 1,
        'icon'       => 'bi bi-bezier2',
        'route_name' => '/configuration/permissions',
        'has_child'  => 'N',
        'has_route'  => 'Y',
        'order_line' => '10.3',
        'guard_name' => 'web'
    ]);

    Permission::create([
        'name'       => 'roles-index',
        'menu_name'  => 'Roles',
        'parent_id'  => 1,
        'icon'       => 'bi bi-bezier',
        'route_name' => '/configuration/roles',
        'has_child'  => 'N',
        'has_route'  => 'Y',
        'order_line' => '10.2',
        'guard_name' => 'web'
    ]);

    Permission::create([
        'name'       => 'roles-store',
        'menu_name'  => 'Roles',
        'parent_id'  => 1,
        'icon'       => 'bi bi-bezier',
        'route_name' => '/configuration/roles',
        'has_child'  => 'N',
        'has_route'  => 'Y',
        'order_line' => '10.2',
        'guard_name' => 'web'
    ]);

    Permission::create([
        'name'       => 'roles-edit',
        'menu_name'  => 'Roles',
        'parent_id'  => 1,
        'icon'       => 'bi bi-bezier',
        'route_name' => '/configuration/roles',
        'has_child'  => 'N',
        'has_route'  => 'Y',
        'order_line' => '10.2',
        'guard_name' => 'web'
    ]);

    Permission::create([
        'name'       => 'roles-erase',
        'menu_name'  => 'Roles',
        'parent_id'  => 1,
        'icon'       => 'bi bi-bezier',
        'route_name' => '/configuration/roles',
        'has_child'  => 'N',
        'has_route'  => 'Y',
        'order_line' => '10.2',
        'guard_name' => 'web'
    ]);

    Permission::create([
        'name'       => 'users-index',
        'menu_name'  => 'Users',
        'parent_id'  => 1,
        'icon'       => 'bi bi-person-bounding-box',
        'route_name' => '/configuration/users',
        'has_child'  => 'N',
        'has_route'  => 'Y',
        'order_line' => '10.1',
        'guard_name' => 'web'
    ]);

    Permission::create([
        'name'       => 'users-store',
        'menu_name'  => 'Users',
        'parent_id'  => 1,
        'icon'       => 'bi bi-person-bounding-box',
        'route_name' => '/configuration/users',
        'has_child'  => 'N',
        'has_route'  => 'Y',
        'order_line' => '10.1',
        'guard_name' => 'web'
    ]);

    Permission::create([
        'name'       => 'users-edit',
        'menu_name'  => 'Users',
        'parent_id'  => 1,
        'icon'       => 'bi bi-person-bounding-box',
        'route_name' => '/configuration/users',
        'has_child'  => 'N',
        'has_route'  => 'Y',
        'order_line' => '10.1',
        'guard_name' => 'web'
    ]);

    Permission::create([
        'name'       => 'users-erase',
        'menu_name'  => 'Users',
        'parent_id'  => 1,
        'icon'       => 'bi bi-person-bounding-box',
        'route_name' => '/configuration/users',
        'has_child'  => 'N',
        'has_route'  => 'Y',
        'order_line' => '10.1',
        'guard_name' => 'web'
    ]);

    $user1 = User::factory()->create([
        'name'       => 'Admin Core',
        'username'   => 'admin',
        'email'      => 'admintesting@gmail.com',
        'password'   => md5(env('SALT_APP').'12345'.env('SALT_APP')),
        'role_id'    => 1,
        'status'     => 1
    ]);

    $superadminRole =  User::where('username', 'admin')->first();
    $superadminRole->givePermissionTo('configuration-index');

    $permissions = $superadminRole->givePermissionTo('permissions-index');
    $superadminRole->givePermissionTo('permissions-store');
    $superadminRole->givePermissionTo('permissions-edit');
    $superadminRole->givePermissionTo('permissions-erase');

    $superadminRole->givePermissionTo('roles-index');
    $superadminRole->givePermissionTo('roles-store');
    $superadminRole->givePermissionTo('roles-edit');
    $superadminRole->givePermissionTo('roles-erase');

    $superadminRole->givePermissionTo('users-index');
    $superadminRole->givePermissionTo('users-store');
    $superadminRole->givePermissionTo('users-edit');
    $superadminRole->givePermissionTo('users-erase');

    // create Role
    $superadminRole = Role::create([
        'name'       => 'superadmin',
        'guard_name' => 'web'
    ]);

    //to role all permissions
    $role = Role::findById(1);
    $role->givePermissionTo(Permission::all());
    $user1->assignRole($superadminRole);

    }
}
