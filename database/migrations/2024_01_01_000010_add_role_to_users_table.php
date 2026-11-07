<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'donor', 'user'])->default('user')->after('email');
            $table->boolean('is_active')->default(true)->after('role');
            $table->string('phone')->nullable()->after('is_active');
            $table->string('district')->nullable()->after('phone');
            $table->string('area')->nullable()->after('district');
            $table->string('avatar')->nullable()->after('area');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active', 'phone', 'district', 'area', 'avatar']);
        });
    }
};
