<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
/////////////////////////////////////add for role and permission
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Creando el Rol
        $role1 = Role::create(['name' => 'Super Admin']);
        $role2 = Role::create(['name' => 'Admin' ]);
        $role3 = Role::create(['name' => 'Leader']);
        $role4 = Role::create(['name' => 'Colaborador']);
        $role5 = Role::create(['name' => 'Report manager']);

        //Creando el permiso para el rol
        $permission1 = Permission::create(['name' => 'Ver todo'])->assignRole([$role1]);
        $permission2 = Permission::create(['name' => 'Administracion'])->syncRoles([$role1, $role2]);
        $permission3 = Permission::create(['name' => 'Ver espacios'])->syncRoles([$role1, $role2]); // $role3
        $permission5 = Permission::create(['name' => 'Ver invitaciones'])->syncRoles([$role1, $role2, $role3, $role4, $role5]);
        $permission6 = Permission::create(['name' => 'Ver agendas'])->syncRoles([$role1, $role2, $role3]);
        $permission7 = Permission::create(['name' => 'Ver reportes'])->assignRole([$role5]);

    }
}
