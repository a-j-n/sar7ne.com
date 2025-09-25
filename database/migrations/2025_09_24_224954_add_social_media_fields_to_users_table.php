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
        Schema::table('users', function (Blueprint $table) {
            // Social media links
            $table->string('social_twitter')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('social_tiktok')->nullable();
            $table->string('social_youtube')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->string('social_github')->nullable();
            $table->string('social_website')->nullable();
            
            // Social media visibility settings
            $table->boolean('social_twitter_public')->default(false);
            $table->boolean('social_instagram_public')->default(false);
            $table->boolean('social_tiktok_public')->default(false);
            $table->boolean('social_youtube_public')->default(false);
            $table->boolean('social_linkedin_public')->default(false);
            $table->boolean('social_github_public')->default(false);
            $table->boolean('social_website_public')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'social_twitter',
                'social_instagram',
                'social_tiktok',
                'social_youtube',
                'social_linkedin',
                'social_github',
                'social_website',
                'social_twitter_public',
                'social_instagram_public',
                'social_tiktok_public',
                'social_youtube_public',
                'social_linkedin_public',
                'social_github_public',
                'social_website_public',
            ]);
        });
    }
};
