<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class CreateFilesPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call( 'db:seed', [
            '--class' => 'FilePermissionsSeeder',
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
                /** elimino los permisos */
                Permission::where(['name'=>'Ver Archivos'])->firstOrFail()->delete();
                Permission::where(['name'=>'Administrar Archivos'])->firstOrFail()->delete();
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                echo $e->getMessage();
            }

            DB::commit();
    }
}
