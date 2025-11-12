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
        Schema::create('task', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->tinyText('description')->nullable();
            $table->bigInteger('status_id')->unsigned()->nullable();
            $table->bigInteger('creator_id')->unsigned()->nullable();
            $table->bigInteger('assignee_id')->unsigned()->nullable();
            $table->text('report')->nullable();
            $table->timestamps();

            $table->foreign('status_id', 'fk_status_id_in_task')
                    ->references('id')
                    ->on('status')
                    ->onUpdate('CASCADE')
                    ->onDelete('set null');
            $table->foreign('creator_id', 'fk_creator_id_in_task')
                    ->references('id')
                    ->on('users')
                    ->onUpdate('CASCADE')
                    ->onDelete('set null');
            $table->foreign('assignee_id', 'fk_assignee_id_in_task')
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
        Schema::dropIfExists('task');
    }
};
