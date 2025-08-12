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
        Schema::table('news', function (Blueprint $table) {
            // Índices para melhorar performance de consultas
            $table->index('published_at', 'idx_news_published_at');
            $table->index('category', 'idx_news_category');
            $table->index('source_name', 'idx_news_source_name');
            $table->index('author', 'idx_news_author');
            $table->index('title', 'idx_news_title');
            
            // Índices compostos para consultas mais complexas
            $table->index(['category', 'published_at'], 'idx_news_category_published');
            $table->index(['source_name', 'published_at'], 'idx_news_source_published');
            $table->index(['published_at', 'category'], 'idx_news_published_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropIndex('idx_news_published_at');
            $table->dropIndex('idx_news_category');
            $table->dropIndex('idx_news_source_name');
            $table->dropIndex('idx_news_author');
            $table->dropIndex('idx_news_title');
            $table->dropIndex('idx_news_category_published');
            $table->dropIndex('idx_news_source_published');
            $table->dropIndex('idx_news_published_category');
        });
    }
};
