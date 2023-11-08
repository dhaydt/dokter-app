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
        Schema::table('resep_obats', function (Blueprint $table) {
            $table->string('code_uniq_resep', 20)->default('RB000');
            $table->string('code_uniq_obat', 20)->default('OB000');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resep_obats', function (Blueprint $table) {
            //
        });
    }
};
