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
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sparepart_id')
                  ->constrained('spareparts')
                  ->cascadeOnDelete()
                  ->comment('Sparepart yang diprediksi');

            $table->date('bulan_prediksi')->comment('Tanggal pertama bulan prediksi, contoh: 2026-06-01');
            $table->decimal('estimasi_kebutuhan', 10, 2)->comment('Estimasi unit yang dibutuhkan dari model Prophet');
            $table->decimal('batas_bawah', 10, 2)->nullable()->comment('Lower bound confidence interval dari Prophet');
            $table->decimal('batas_atas', 10, 2)->nullable()->comment('Upper bound confidence interval dari Prophet');

            $table->string('versi_model')->nullable()->comment('Versi/run-id model ML untuk audit trail');
            $table->timestamp('di_generate_pada')->nullable()->comment('Waktu model Prophet di-run');

            $table->timestamps();

            // Constraint: satu prediksi per sparepart per bulan
            $table->unique(['sparepart_id', 'bulan_prediksi'], 'unique_prediction_per_month');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
