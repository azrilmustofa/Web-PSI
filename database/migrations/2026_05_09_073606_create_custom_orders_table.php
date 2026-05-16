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
        Schema::create('custom_orders', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')->constrained()->onDelete('cascade');

    $table->string('jenis_furniture');
    $table->string('jenis_kayu');
    $table->string('gambar', 255)->nullable();
    $table->string('ukuran');

    $table->text('catatan')->nullable();

    $table->bigInteger('estimasi_harga')->nullable();

    $table->enum('status', [
        'pending',
        'diproses',
        'selesai'
    ])->default('pending');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_orders');
    }
};
