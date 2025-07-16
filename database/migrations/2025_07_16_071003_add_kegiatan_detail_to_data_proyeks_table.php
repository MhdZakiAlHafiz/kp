<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('data_proyeks', function (Blueprint $table) {
            $table->json('kegiatan_detail')->nullable()->after('nomor_catatan_permintaan');
        });
    }

    public function down(): void
    {
        Schema::table('data_proyeks', function (Blueprint $table) {
            $table->dropColumn('kegiatan_detail');
        });
    }
};
