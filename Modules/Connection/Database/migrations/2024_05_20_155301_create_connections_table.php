<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('connections', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignUuid('user_uuid')->nullable();
            $table->string('endpoint');
            $table->string('register');
            $table->string('login');
            $table->string('get_log');
            $table->string('get_log_by_type');
            $table->string('get_log_by_time');
            $table->string('delete_log');
            $table->string('delete_log_by_type');
            $table->string('delete_log_by_time');
            $table->string('token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connections');
    }
};
