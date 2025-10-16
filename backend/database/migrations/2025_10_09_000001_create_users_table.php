<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['patient', 'doctor', 'admin'])->default('patient');
            $table->rememberToken();
            $table->timestamps();
            $table->string('api_token', 80)->unique()->nullable();
            $table->timestamp('token_expires_at')->nullable();

            $table->unique(['email', 'role']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
