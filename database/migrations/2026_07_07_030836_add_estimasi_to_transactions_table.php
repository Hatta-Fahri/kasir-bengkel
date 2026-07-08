<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom status baru (estimasi, disetujui, proses, selesai, batal)
        //    dan kolom untuk menyimpan total jasa terpisah
        Schema::table('transactions', function (Blueprint $table) {
            // Kolom baru: total ongkos jasa dari pilihan jasa servis
            // (menggantikan input manual; nilai lama tetap bisa tersimpan)
            $table->json('jasa_items')->nullable()->after('ongkos_jasa')
                  ->comment('Snapshot jasa servis yang dipilih: [{jasa_servis_id, nama_jasa, estimasi_biaya}]');
        });

        // 2. Ubah ENUM status: tambah nilai baru (estimasi, disetujui, proses)
        //    Nilai lama 'selesai' dan 'batal' tetap ada
        DB::statement("ALTER TABLE transactions MODIFY status ENUM(
            'estimasi',
            'disetujui',
            'proses',
            'selesai',
            'batal'
        ) NOT NULL DEFAULT 'selesai'");
    }

    public function down(): void
    {
        // Kembalikan ENUM status ke semula
        DB::statement("ALTER TABLE transactions MODIFY status ENUM('selesai', 'batal') NOT NULL DEFAULT 'selesai'");

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('jasa_items');
        });
    }
};
