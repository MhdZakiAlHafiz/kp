<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\progress;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_proyeks', function (Blueprint $table) {
            $table->id();

            $table->string('nomor_cr');
            $table->string('owner');
            $table->string('jenis');


            $table->string('target');
            $table->string('target_disepakati');
            $table->string('target_kesepakatan');

            $table->text('detail_pengembangan');

            $table->text('pic_perencana');
            $table->text('pic_pelaksana');

            $table->text('keterangan');

            $table->float('progres')->default(0);
            $table->enum('status', ['Not Started', 'On Progress', 'Completed'])->default('Not Started');

            $table->string('nomor_catatan_permintaan')->nullable();
            $table->timestamps();
            $table->json('kegiatan_detail')->nullable();

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
