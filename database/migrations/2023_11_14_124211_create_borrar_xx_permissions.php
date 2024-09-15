<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class CreateBorrarXxPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call( 'db:seed', [
          '--class' => 'PermissionSeederBorrar',
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
            try{
                /** elimino los permisos */
                Permission::where(['name'=>'Borrar Provincia'])->firstOrFail()->delete();
                Permission::where(['name'=>'Borrar Departamento'])->firstOrFail()->delete();
                Permission::where(['name'=>'Borrar Localdiad'])->firstOrFail()->delete();
                Permission::where(['name'=>'Borrar Fracción'])->firstOrFail()->delete();
                Permission::where(['name'=>'Borrar Radio'])->firstOrFail()->delete();
                Permission::where(['name'=>'Borrar Aglomerado'])->firstOrFail()->delete();
                Permission::where(['name'=>'Borrar Paraje'])->firstOrFail()->delete();
                Permission::where(['name'=>'Borrar Entidad'])->firstOrFail()->delete();
                Permission::where(['name'=>'Borrar Base Antártica'])->firstOrFail()->delete();
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                echo $e->getMessage();
            }
            
            DB::commit();
    }
}
