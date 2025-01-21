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
        Schema::create('materials', function (Blueprint $table) {
            $table->id('material_id');
            $table->string('material_name');
            $table->string('material_stock');
            $table->string('id_category');
            $table->string('material_size')->nullable();
            $table->string('material_quantity')->nullable();
            $table->string('material_panjang')->nullable();
            $table->string('material_lebar')->nullable();
            $table->string('material_price');
            $table->string('material_unit')->nullable();
            $table->string('p_default')->nullable();
            $table->string('l_default')->nullable();
            $table->string('q_default')->nullable();
            
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
