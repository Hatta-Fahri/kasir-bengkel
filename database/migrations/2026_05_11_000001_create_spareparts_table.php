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
        Schema::create('spareparts', function (Blueprint $table) {
            $table->id();
            $table->string('kode_part')->unique()->comment('Kode unik sparepart, contoh: SP-001');
            $table->string('nama_part');
            $table->string('merek')->nullable()->comment('Merek/brand sparepart');
            $table->string('kategori')->nullable()->comment('Contoh: Oli, Filter, Kampas Rem, dsb');
            $table->unsignedInteger('stok')->default(0);
            $table->unsignedInteger('stok_minimum')->default(5)->comment('Batas stok minimum untuk notifikasi restok');
            $table->decimal('harga_beli', 12, 2)->comment('Harga beli per unit (HPP)');
            // harga_jual TIDAK disimpan di DB; dihitung via Accessor di Model (+10%)
            $table->string('satuan')->default('pcs')->comment('Satuan: pcs, liter, set, dll');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spareparts');
    }
};
