<?php

namespace Database\seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class FilterPermissionsSeeder extends Seeder
{
    /**
     * Crea los permisos necesarios para administrar los filtros del sistema.
     *
     * @return void
     */
    public function run()
    {
        $arrayOfPermissionNames = ['Crear Filtros', 'Editar Filtros', 'Eliminar Filtros'];

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
