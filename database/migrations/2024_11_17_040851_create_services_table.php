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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name_services');
        $table->string('id_material')->nullable();
        $table->string('id_category');
        $table->string('descriptions');
        $table->string('price');
        $table->string('customize');
        $table->string('image');
        // kusus 
        $table->text('isCustomize', 15);
        $table->text('input_type', 15)->default('normal');
        $table->text('data_custom');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
