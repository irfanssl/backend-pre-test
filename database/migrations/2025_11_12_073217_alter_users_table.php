<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function(Blueprint $table){
            $table->bigInteger('role_id')->unsigned()->nullable();
            $table->bigInteger('manager_id')->unsigned()->nullable();

            $table->foreign('role_id', 'fk_role_id_in_users')
                    ->references('id')
                    ->on('role')
                    ->onUpdate('CASCADE')
                    ->onDelete('set null');
            $table->foreign('manager_id', 'fk_manager_id_in_users')
                    ->references('id')
                    ->on('users')
                    ->onUpdate('CASCADE')
                    ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function(Blueprint $table){
            $table->dropForeign(['fk_role_id_in_users']);
            $table->dropForeign(['fk_manager_id_in_users']);
            
            $table->dropColumn('role_id');
            $table->dropColumn('manager_id');
        });
    }
};
