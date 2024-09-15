<?php

namespace Database\seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UserPermissionsSeeder extends Seeder
{
    /**
     * Crea los permisos necesarios para administrar permisos, filtros y roles de los usuarios.
     *
     * @return void
     */
    public function run()
    {
        $arrayOfPermissionNames = ['Administrar Permisos', 'Administrar Filtros', 'Administrar Roles'];

        $permissions = collect($arrayOfPermissionNames)->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'web'];
        });

        foreach ($permissions as $permission) {
            $this->command->info('Creando permiso '.$permission['name']);
            try{
                Permission::firstOrcreate($permission);
                $this->command->info('Permiso '.$permission['name'].' creado.');
            } catch ( Spatie\Permission\Exception $e) {
                $this->command->error('Error creando permiso '.$permission['name'].'...');
                echo __($e->getMessage());
            }
        }
    }
}
