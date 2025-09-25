<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table): void {
            $table->boolean('is_public')->default(false)->after('status');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->boolean('allow_public_messages')->default(true)->after('gender');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table): void {
            $table->dropColumn(['is_public']);
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['allow_public_messages']);
        });
    }
};
