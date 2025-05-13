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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('user_nik', 50)->index();
            $table->string('user_name');
            $table->string('user_fullname');
            $table->string('user_email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('user_role_id')->default(2)->nullable();
            $table->string('created_by', 50)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->timestamp('last_login')->nullable();
            $table->boolean('is_active')->default('1');
        });

        Schema::create('master_user_roles', function (Blueprint $table) {
            $table->id('user_role_id');
            $table->string('description_role', 100);
            $table->string('created_by', 50)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->boolean('is_active')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('master_user_roles');
    }
};
