<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Elimino los roles del Super Admin.
     * (Son innecesarios ya que se maneja con una gate)
     *
     * @return void
     */
    public function up()
    {
        try{
            $superAdmin = Role::where('name','Super Admin')->first();
            $superAdmin->syncPermissions([]);
        } catch ( Spatie\Permission\Exceptions $e) {
            $this->command->error('Error eliminando los permisos rol Super Admin...');
            echo __($e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
