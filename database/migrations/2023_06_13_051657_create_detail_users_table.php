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
        Schema::create('detail_users', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 20)->nullable();
            $table->string('ttl', 20)->nullable();
            $table->string('umur', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->enum('kelamin', ['laki-laki', 'perempuan'])->default('laki-laki');
            $table->string('phone', 20)->nullable();
            $table->string('berat', 20)->nullable();
            $table->string('tinggi', 20)->nullable();
            $table->string('alergi', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_users');
    }
};
