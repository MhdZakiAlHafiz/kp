<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_proyeks', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_surat', 20); // Ditambahkan di migrasi terpisah, tapi disertakan di sini untuk kelengkapan
            $table->string('nomor_cr');
            $table->string('owner');
            $table->string('jenis');
            $table->string('target');
            $table->string('target_disepakati');
            $table->string('target_kesepakatan');
            $table->text('detail_pengembangan');
            $table->text('pic_perencana'); // Akan menyimpan JSON string
            $table->text('pic_pelaksana'); // Akan menyimpan JSON string
            $table->text('keterangan');
            $table->float('progres')->default(0);
            $table->enum('status', ['Not Started', 'On Progress', 'Completed'])->default('Not Started');
            $table->string('nomor_catatan_permintaan')->nullable();
            $table->json('kegiatan_detail')->nullable(); // Kolom JSON untuk detail kegiatan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_proyeks');
    }
};
