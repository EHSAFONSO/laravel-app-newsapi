<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'url',
        'alt_text',
        'title',
        'width',
        'height',
        'score',
        'type',
        'is_accessible'
    ];

    protected $casts = [
        'is_accessible' => 'boolean',
        'width' => 'integer',
        'height' => 'integer',
        'score' => 'integer'
    ];

    /**
     * Relacionamento com a notícia
     */
    public function news()
    {
        return $this->belongsTo(News::class);
    }

    /**
     * Scope para imagens principais
     */
    public function scopeMain($query)
    {
        return $query->where('type', 'main');
    }

    /**
     * Scope para imagens de conteúdo
     */
    public function scopeContent($query)
    {
        return $query->where('type', 'content');
    }

    /**
     * Scope para imagens de galeria
     */
    public function scopeGallery($query)
    {
        return $query->where('type', 'gallery');
    }

    /**
     * Scope para imagens acessíveis
     */
    public function scopeAccessible($query)
    {
        return $query->where('is_accessible', true);
    }

    /**
     * Obter a melhor imagem (maior score)
     */
    public function scopeBest($query)
    {
        return $query->orderBy('score', 'desc');
    }

    /**
     * Calcular área da imagem
     */
    public function getAreaAttribute()
    {
        if ($this->width && $this->height) {
            return $this->width * $this->height;
        }
        return null;
    }

    /**
     * Verificar se é uma imagem principal
     */
    public function isMain()
    {
        return $this->type === 'main';
    }

    /**
     * Verificar se é uma imagem de conteúdo
     */
    public function isContent()
    {
        return $this->type === 'content';
    }

    /**
     * Verificar se é uma imagem de galeria
     */
    public function isGallery()
    {
        return $this->type === 'gallery';
    }
}
