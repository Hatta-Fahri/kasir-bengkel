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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')
                  ->constrained('transactions')
                  ->cascadeOnDelete()
                  ->comment('Relasi ke transaksi induk');

            $table->foreignId('sparepart_id')
                  ->constrained('spareparts')
                  ->restrictOnDelete()
                  ->comment('Sparepart yang terjual');

            $table->unsignedInteger('qty')->comment('Jumlah unit yang terjual');

            // Snapshot harga SAAT transaksi terjadi (penting! harga bisa berubah di masa depan)
            $table->decimal('harga_beli_saat_transaksi', 12, 2)->comment('Snapshot harga beli HPP saat transaksi');
            $table->decimal('harga_jual_saat_transaksi', 12, 2)->comment('Snapshot harga jual (HPP+10%) saat transaksi');
            $table->decimal('subtotal', 12, 2)->comment('qty * harga_jual_saat_transaksi');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
