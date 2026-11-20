<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {  // ← GANTI 'user' JADI 'users'
            $table->enum('role', ['admin', 'customer'])->default('customer')->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {  // ← GANTI JUGA DI SINI
            $table->dropColumn('role');
        });
    }
};