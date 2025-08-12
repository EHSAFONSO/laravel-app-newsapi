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
        Schema::create('news_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('news_id');
            $table->string('url');
            $table->string('alt_text')->nullable();
            $table->string('title')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('score')->default(0); // Score para ordenação
            $table->enum('type', ['main', 'content', 'gallery'])->default('content');
            $table->boolean('is_accessible')->default(true);
            $table->timestamps();
            
            // Índices
            $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
            $table->index(['news_id', 'type']);
            $table->index('score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_images');
    }
};
