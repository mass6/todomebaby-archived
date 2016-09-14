<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('user_id')->unsigned();
            $table->string('title');
            $table->boolean('complete')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->boolean('next')->default(false);
            $table->date('due_date')->nullable();
            $table->uuid('project_id')->nullable();
            $table->char('priority', 3)->nullable();
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tasks');
    }
}
