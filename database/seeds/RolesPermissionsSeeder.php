<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permissions = [
            ['name' => 'create posts'],
            ['name' => 'update posts'],
            ['name' => 'delete posts'],
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission['name']]);
        }

        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
            ],
            [
                'name' => 'moderator',
                'display_name' => 'Moderator',
                'permissions' => [
                    'create posts',
                    'update posts',
                    'delete posts',
                ]
            ],
            [
                'name' => 'user',
                'display_name' => 'User',
            ]
        ];

        foreach ($roles as $data) {
            $role = Role::create(['name' => $data['name']]);
            if($permissions = array_get($data, 'permissions')){
                foreach($permissions as $permission){
                    $role->givePermissionTo($permission);
                }
            }
        }

    }
}
