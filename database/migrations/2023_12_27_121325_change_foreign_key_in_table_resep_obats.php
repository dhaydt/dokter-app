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
            $table->dropForeign(['resep_id']);
            
            $table->dropForeign(['obat_id']);
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
