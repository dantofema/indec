<?php

namespace Database\seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeederBorrar extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrayOfPermissionNames = ['Borrar Provincia','Borrar Departamento','Borrar Localdiad','Borrar Fracción','Borrar Radio',
                                   'Borrar Aglomerado', 'Borrar Paraje', 'Borrar Entidad', 'Borrar Base Antártica' ];

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
