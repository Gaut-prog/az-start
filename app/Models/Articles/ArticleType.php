<?php

namespace App\Models\Articles;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticleType extends Model
{
    use HasFactory;

    protected $table = 'article_type';
    protected $primaryKey = 'id_article_type';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'Label',
        'en_Label',
        'Note',
    ];

    public function getTLabelAttribute(): ?string
    {
        return app()->getLocale() === 'fr' ? $this->Label : $this->en_Label;
    }

    public function articles() : HasMany
    {
        return $this->hasMany(Article::class, 'article_type', 'id_article_type');
    }
}