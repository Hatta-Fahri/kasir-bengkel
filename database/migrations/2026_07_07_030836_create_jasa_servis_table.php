<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jasa_servis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jasa')->comment('Nama pekerjaan jasa, contoh: Cuci Evaporator');
            $table->decimal('estimasi_biaya', 12, 2)->comment('Estimasi harga/biaya jasa');
            $table->text('keterangan')->nullable()->comment('Deskripsi atau catatan tambahan');
            $table->boolean('is_aktif')->default(true)->comment('Apakah jasa ini aktif ditawarkan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jasa_servis');
    }
};
