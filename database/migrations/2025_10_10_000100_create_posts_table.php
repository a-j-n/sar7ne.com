<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_anonymous')->default(false);
            $table->string('content', 500)->nullable();
            $table->json('images')->nullable();
            $table->string('delete_token_hash')->nullable()->index();
            $table->string('anon_key_hash')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
