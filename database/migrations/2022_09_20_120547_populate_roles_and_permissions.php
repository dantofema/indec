<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class PopulateRolesAndPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call( 'db:seed', [
            '--class' => 'RoleSeeder',
            '--force' => true ]
        );

        Artisan::call( 'db:seed', [
            '--class' => 'DefaultUsersSeeder',
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
            /** le quito el rol al super admin y lo elimino */
            $superAdmin = User::where(['email'=>'superadmin@segmenter'])->first();
            $superAdmin->removeRole('Super Admin');
            $superAdmin->delete();
            
            /** le quito los permisos al rol y lo elimino*/
            $superAdmin = Role::where(['name'=>'Super Admin'])->first();
            $superAdmin->syncPermissions([]);
            $superAdmin->delete();
            DB::commit();
        
    }
}
