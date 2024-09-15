<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        if (!Schema::hasTable('permissions')) {
            Schema::create($tableNames['permissions'], function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('guard_name');
                $table->timestamps();
            });
        } else {
            echo __('Ya existe la tabla permissions');
        }

        if (!Schema::hasTable('roles')) {
            Schema::create($tableNames['roles'], function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('guard_name');
                $table->timestamps();
            });
        } else {
            echo __('Ya existe la tabla roles');
        }

        if (!Schema::hasTable('model_has_permissions')) {
            Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
                $table->unsignedBigInteger('permission_id');

                $table->string('model_type');
                $table->unsignedBigInteger($columnNames['model_morph_key']);
                $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

                $table->foreign('permission_id')
                    ->references('id')
                    ->on($tableNames['permissions'])
                    ->onDelete('cascade');

                $table->primary(['permission_id', $columnNames['model_morph_key'], 'model_type'],
                        'model_has_permissions_permission_model_type_primary');
            });
        } else {
            echo __('Ya existe la tabla model_has_permissions');
        }

        if (!Schema::hasTable('model_has_roles')) {
        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedBigInteger('role_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['role_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary');
        });
        } else {
            echo __('Ya existe la tabla model_has_roles');
        }

        if (!Schema::hasTable('role_has_permissions')) {
        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
        });
        } else {
            echo __('Ya existe la tabla role_has_permissions');
        }

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
        }
            Schema::dropIfExists($tableNames['role_has_permissions']);
            Schema::dropIfExists($tableNames['model_has_roles']);
            Schema::dropIfExists($tableNames['model_has_permissions']);
            Schema::dropIfExists($tableNames['roles']);
            Schema::dropIfExists($tableNames['permissions']);
    }
}
