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
        Schema::create('subnets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('preshared_key');
            $table->string('private_key');
            $table->ipAddress('network');
            $table->integer('port');
            $table->integer('mask');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subnets');
    }
};
