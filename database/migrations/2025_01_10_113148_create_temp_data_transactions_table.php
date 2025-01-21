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
        Schema::create('temp_data_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id');
            $table->string('service_id', 20);
            $table->integer('jumlah');
            $table->decimal('harga', 10, 2); // Presisi untuk harga
            $table->integer('quantity')->nullable()->default(0);
            $table->integer('panjang')->nullable()->default(0);
            $table->integer('lebar')->nullable()->default(0);
            $table->text('image')->nullable();
            $table->text('lampiran')->nullable();
            $table->text('id_material')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_data_transactions');
    }
};
