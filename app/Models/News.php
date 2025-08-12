<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'content',
        'author',
        'published_at',
        'source_name',
        'url',
        'url_to_image',
        'category'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Relacionamento com as imagens
     */
    public function images()
    {
        return $this->hasMany(NewsImage::class);
    }

    /**
     * Obter a imagem principal
     */
    public function mainImage()
    {
        return $this->hasOne(NewsImage::class)->main()->best();
    }

    /**
     * Obter imagens de conteúdo
     */
    public function contentImages()
    {
        return $this->hasMany(NewsImage::class)->content()->accessible()->best();
    }

    /**
     * Obter imagens de galeria
     */
    public function galleryImages()
    {
        return $this->hasMany(NewsImage::class)->gallery()->accessible()->best();
    }

    /**
     * Obter a melhor imagem (principal ou melhor score)
     */
    public function bestImage()
    {
        return $this->hasOne(NewsImage::class)->accessible()->best();
    }

    /**
     * Verificar se tem imagens
     */
    public function hasImages()
    {
        return $this->images()->exists();
    }

    /**
     * Verificar se tem imagem principal
     */
    public function hasMainImage()
    {
        return $this->mainImage()->exists();
    }

    /**
     * Obter URL da melhor imagem disponível
     */
    public function getBestImageUrlAttribute()
    {
        // Primeiro tenta a imagem principal
        $mainImage = $this->mainImage()->first();
        if ($mainImage) {
            return $mainImage->url;
        }

        // Depois tenta a melhor imagem por score
        $bestImage = $this->bestImage()->first();
        if ($bestImage) {
            return $bestImage->url;
        }

        // Fallback para url_to_image original
        return $this->url_to_image;
    }

    /**
     * Adicionar imagem à notícia
     */
    public function addImage($imageData)
    {
        return $this->images()->create($imageData);
    }

    /**
     * Definir imagem principal
     */
    public function setMainImage($imageId)
    {
        // Remove imagem principal atual
        $this->images()->main()->update(['type' => 'content']);
        
        // Define nova imagem principal
        return $this->images()->where('id', $imageId)->update(['type' => 'main']);
    }

    /**
     * Atualizar scores das imagens
     */
    public function updateImageScores()
    {
        $this->images()->each(function ($image) {
            $score = $this->calculateImageScore(
                $image->url,
                $image->alt_text,
                $image->title,
                $image->width,
                $image->height
            );
            $image->update(['score' => $score]);
        });
    }

    /**
     * Calcular score de uma imagem
     */
    private function calculateImageScore($url, $alt, $title, $width, $height)
    {
        $score = 0;
        
        // Pontuar por tamanho
        if ($width && $height) {
            $area = (int)$width * (int)$height;
            if ($area > 100000) $score += 10;
            elseif ($area > 50000) $score += 8;
            elseif ($area > 25000) $score += 6;
            elseif ($area > 10000) $score += 4;
        }
        
        // Pontuar por texto alternativo
        if (!empty($alt)) $score += 5;
        if (!empty($title)) $score += 3;
        
        // Pontuar por extensão
        $extension = strtolower(pathinfo($url, PATHINFO_EXTENSION));
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) $score += 2;
        
        // Penalizar ícones/logos
        if (strpos($url, 'icon') !== false) $score -= 5;
        if (strpos($url, 'logo') !== false) $score -= 3;
        if (strpos($url, 'avatar') !== false) $score -= 5;
        
        return $score;
    }
}
