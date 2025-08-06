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
        // Migrasi untuk tabel 'roles'
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Migrasi untuk tabel 'users'
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('status', ['submitted', 'approved', 'rejected'])->default('submitted');
            $table->unsignedBigInteger('role_id'); // Kolom untuk foreign key
            $table->rememberToken();
            $table->timestamps();

            // Definisi foreign key, perbaiki 'casecade ' menjadi 'cascade' (tanpa spasi)
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        // Migrasi untuk tabel 'password_reset_tokens'
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Migrasi untuk tabel 'sessions'
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Pastikan untuk menghapus tabel yang memiliki foreign key terlebih dahulu
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles'); // Hapus tabel roles terakhir
    }
};
