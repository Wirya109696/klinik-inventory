<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // reset cahced roles and permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        // Permission::create([
        //     'name'       => 'dashboard-index',
        //     'menu_name'  => 'Dashboard',
        //     'icon'       => 'bi bi-house-door',
        //     'route_name' => '/dashboard',
        //     'order_line' => '1',

        // ]);
        // Permission::create([
        //     'name'      => 'user-store',
        //     'menu_name' => 'User',
        // ]);
        // Permission::create([
        //     'name'      => 'user-edits',
        //     'menu_name' => 'User',
        // ]);
        // Permission::create([
        //     'name'      => 'user-destroy',
        //     'menu_name' => 'User',
        // ]);
        
        // Permission::create([
        //     'name' => 'publish menu',
        //     'menu_name' => 'Publish',
        // ]);
        // Permission::create([
        //     'name' => 'unpublish menu',
        //     'menu_name' => 'UnPublish',
        // ]);

        $user1 = User::factory()->create([
            'name'       => 'ROZUL IMAM',
            'username'   => '88101731',
            'email'      => 'tio@gmail.com',
            'perusahaan' => 'PT IMIP',
            'divisi'     => 'IT - APPLICATION DEVELOPMENT',
            'password'   => bcrypt('12345678'),
        ]);
        

        $user2 = User::factory()->create([
            'name'       => 'HEVI SISWOYO',
            'username'   => '88100085',
            'email'      => 'tio31@gmail.com',
            'perusahaan' => 'PT IMIP',
            'divisi'     => 'IT - APPLICATION DEVELOPMENT',
            'password'   => bcrypt('12345678'),
        ]);
        

        $user3 = User::factory()->create([
            'name'       => 'Satrio Setiawan I Lintang',
            'username'   => '88102332',
            'email'      => 'tiox@gmail.com',
            'perusahaan' => 'PT IMIP',
            'divisi'     => 'IT - APPLICATION DEVELOPMENT',
            'password'   => bcrypt('12345678'),
            'role_id'    => 1
        ]);

        $user4 = User::factory()->create([
            'name'       => 'PUTU NGURAH PRAYOGA',
            'username'   => '88102635',
            'email'      => 'yoga@gmail.com',
            'perusahaan' => 'PT IMIP',
            'role_id'    => 2,
            'divisi'     => 'IT SAP WEB PROGRAMMER',
            'password'   => bcrypt('12345678'),
        ]);
       

        //create roles and assign existing permissions
        // $admin = User::where('username', '88102332')->first();
        // $admin->givePermissionTo('dashboard-index');
        // $writerRole->givePermissionTo('user-store');
        // $writerRole->givePermissionTo('user-edits');

        // $adminRole =  User::where('username', '88100085')->first();
        // $adminRole->givePermissionTo('user-index');
        // $adminRole->givePermissionTo('user-store');
        // $adminRole->givePermissionTo('user-edits');
        // $adminRole->givePermissionTo('publish menu');

        // $superadminRole =  User::where('username', '88102332')->first();
        // $superadminRole->givePermissionTo('user-index');
        // $superadminRole->givePermissionTo('user-store');
        // $superadminRole->givePermissionTo('user-edits');
        // $superadminRole->givePermissionTo('user-destroy');
        // $superadminRole->givePermissionTo('publish menu');
        // $superadminRole->givePermissionTo('unpublish menu');
        // gets all permissions via Gate::before rule

        // create Role
        $superadminRole = Role::create(['name' => 'superadmin']);
        $adminRole      = Role::create(['name' => 'admin']);
        $personalRole   = Role::create(['name' => 'personal']);
       
        //arahkan role
        // $user3->assignRole($superadminRole);
        // $user3->assignRole($superadminRole);
    }
}
