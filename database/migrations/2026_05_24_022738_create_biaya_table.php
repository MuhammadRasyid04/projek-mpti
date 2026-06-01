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
        Schema::create('biaya', function (Blueprint $table) {
            $table->id('id_biaya');
            $table->foreignId('id_hunian')->constrained('hunian', 'id_hunian')->onDelete('cascade');
            $table->decimal('listrik', 10, 2);
            $table->decimal('air', 10, 2);
            $table->decimal('sampah', 10, 2);
            $table->decimal('kost', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biaya');
    }
};
