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
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('role')->nullable();
            $table->tinyInteger('is_delete')->default(0);
            $table->string('type')->default('fixed');
            $table->integer('shift')->nullable();
            $table->integer('sclary')->nullable();
            $table->integer('branch')->nullable();
            $table->tinyInteger('plan_id')->nullable();
            $table->string('fcm_token')->nullable();
            $table->string('push_notification')->nullable();
            $table->rememberToken()->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->string('registration_no');
            $table->string('tz')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
        

       

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
