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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->string('resep_id', 50)->nullable();
            $table->string('hari_ke', 50)->nullable();
            $table->date('tanggal')->nullable();
            $table->dateTime('waktu_minum')->nullable();
            $table->string('img', 255)->nullable();
            $table->enum('status', ['pending', 'done', 'fail'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
