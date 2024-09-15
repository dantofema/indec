<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Exceptions;

class RoleSeeder extends Seeder
{
    /**
     * Crea el rol Super Admin.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        try{
            $this->command->info('Creando rol Super Admin');
            $superAdmin = Role::firstOrcreate(['name' => 'Super Admin']);
            $this->command->info('Rol Super Admin creado.');
        } catch ( Spatie\Permission\Exceptions $e) {
            $this->command->error('Error creando rol Super Admin...');
            echo __($e->getMessage());
        }
    }
}
