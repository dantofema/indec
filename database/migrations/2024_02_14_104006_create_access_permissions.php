<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call( 'db:seed', [
            '--class' => 'UserPermissionsSeeder',
            '--force' => true ]
        );
        Artisan::call( 'db:seed', [
            '--class' => 'FilterPermissionsSeeder',
            '--force' => true ]
        );
        Artisan::call( 'db:seed', [
            '--class' => 'RolePermissionsSeeder',
            '--force' => true ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::beginTransaction();
            try {
                /** elimino los permisos de administración de usuarios*/
                Permission::where(['name'=>'Administrar Permisos'])->first()->delete();
                Permission::where(['name'=>'Administrar Filtros'])->first()->delete();
                Permission::where(['name'=>'Administrar Roles'])->first()->delete();

                /** elimino los permisos de administración de filtros*/
                Permission::where(['name'=>'Crear Filtros'])->first()->delete();
                Permission::where(['name'=>'Editar Filtros'])->first()->delete();
                Permission::where(['name'=>'Eliminar Filtros'])->first()->delete();

                /** elimino los permisos de administración de roles*/
                Permission::where(['name'=>'Crear Roles'])->first()->delete();
                Permission::where(['name'=>'Editar Roles'])->first()->delete();
                Permission::where(['name'=>'Eliminar Roles'])->first()->delete();
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                echo $e->getMessage();
            }
            

            DB::commit();
    }
};
