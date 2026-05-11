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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('no_struk')->unique()->comment('Nomor struk unik, contoh: TRX-20260511-001');
            $table->foreignId('kasir_id')
                  ->constrained('users')
                  ->restrictOnDelete()
                  ->comment('User (kasir) yang melakukan transaksi');

            $table->enum('tipe_transaksi', ['penjualan', 'servis'])
                  ->comment('Tipe: penjualan = hanya jual sparepart; servis = pengerjaan kendaraan');

            // Kolom khusus tipe 'servis' (nullable untuk tipe 'penjualan')
            $table->string('plat_nomor')->nullable()->comment('Plat nomor kendaraan, wajib untuk tipe servis');
            $table->string('jenis_mobil')->nullable()->comment('Merek/tipe kendaraan, wajib untuk tipe servis');
            $table->decimal('ongkos_jasa', 12, 2)->default(0)->comment('Biaya jasa pengerjaan, hanya untuk tipe servis');

            // Ringkasan keuangan transaksi
            $table->decimal('subtotal_sparepart', 12, 2)->default(0)->comment('Total harga seluruh sparepart dalam transaksi');
            $table->decimal('total_bayar', 12, 2)->comment('Grand total yang harus dibayar pelanggan');
            $table->enum('metode_pembayaran', ['cash', 'qris'])->default('cash');
            $table->decimal('uang_diterima', 12, 2)->nullable()->comment('Uang yang diberikan pelanggan (untuk metode cash)');
            $table->decimal('kembalian', 12, 2)->nullable()->comment('Kembalian = uang_diterima - total_bayar (untuk cash)');

            $table->enum('status', ['selesai', 'batal'])->default('selesai');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
