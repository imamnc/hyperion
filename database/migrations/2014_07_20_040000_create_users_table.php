<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('password')->nullable();
            $table->string('pin')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('pegawai_id')->nullable();
            $table->text('service_token')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->rememberToken();
            $table->enum('type', ['admin', 'user'])->default('user');
            $table->timestamps();
            $table->foreign('project_id')->on('projects')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
