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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')
                  ->constrained('users')
                  ->restrictOnDelete()
                  ->comment('Admin yang menginput pengeluaran');

            $table->string('nama_pengeluaran')->comment('Deskripsi singkat pengeluaran, contoh: Listrik Mei, Gaji Mekanik');
            $table->string('kategori')->nullable()->comment('Contoh: Operasional, Gaji, Perawatan Gedung');
            $table->decimal('jumlah', 12, 2)->comment('Nominal pengeluaran dalam rupiah');
            $table->date('tanggal_pengeluaran')->comment('Tanggal pengeluaran terjadi (bisa berbeda dari created_at)');
            $table->text('keterangan')->nullable()->comment('Catatan tambahan jika diperlukan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
