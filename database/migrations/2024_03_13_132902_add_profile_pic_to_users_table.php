<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'profile_pic')) {
                    $table->string('profile_pic')->nullable()->after('email');
                }
            });
        } catch (\Exception $e) {
            echo 'Error al crear la columna profile_pic: ' . $e->getMessage();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('profile_pic');
            });
        } catch (\Exception $e) {
            echo 'Error al eliminar la columna profile_pic: ' . $e->getMessage();
        }
    }
};
