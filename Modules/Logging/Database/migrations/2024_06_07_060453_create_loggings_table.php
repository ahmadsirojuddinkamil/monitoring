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
        Schema::create('loggings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignUuid('connection_uuid')->nullable();
            $table->string('type_env')->nullable();
            $table->string('type_log')->nullable();
            $table->string('other')->nullable();
            $table->string('emergency')->nullable();
            $table->string('alert')->nullable();
            $table->string('critical')->nullable();
            $table->string('error')->nullable();
            $table->string('warning')->nullable();
            $table->string('notice')->nullable();
            $table->string('info')->nullable();
            $table->string('debug')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loggings');
    }
};
