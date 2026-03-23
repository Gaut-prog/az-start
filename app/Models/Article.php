<?php

namespace App\Models;

use App\Models\Articles\ArticleComment;
use App\Models\Articles\ArticleType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';
    protected $primaryKey = 'id_article';
    public $timestamps = false;
    protected $connection = 'mysql2';



    // Colonnes modifiables
    protected $fillable = [
        'creation_date',
        'created_by',
        'en_article_tittle',
        'article_tittle',
        'article_type',
        'related_item',
        'related_link',
        'en_article',
        'article',
        'reference_doc',
        'last_update_date',
        'last_update_user',
        'privacy',
        'IsJamiiArticle',
        'picture',
    ];

    protected $hidden = [
        "picture",
        "reference_doc"
    ];
    
    protected $casts = [
        "creation_date" => 'datetime',
        "last_update_date" => 'datetime'
    ];

    /**
     * Accessor pour le titre selon la langue
     */
    public function getTArticleTitleAttribute(): string
    {
        return app()->getLocale() === 'fr' ? $this->article_tittle : $this->en_article_tittle;
    }

    /**
     * Accessor pour le contenu selon la langue
     */
    public function getContentAttribute(): ?string
    {
        return app()->getLocale() === 'fr' ? $this->article : $this->en_article;
    }

    /**
     * Accessor pour l'image ou document en base64
     */
    public function getFPictureAttribute(): ?string
    {
        return $this->picture
            ? 'data:image/jpeg;base64,' . base64_encode($this->picture)
            : null;
    }

    public function getFReferenceDocAttribute(): ?string
    {
        return $this->reference_doc
            ? 'data:application/pdf;base64,' . base64_encode($this->reference_doc)
            : null;
        
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ArticleType::class, 'article_type', 'id_article_type');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'related_item', 'Items_Numbers');
    }
    public function author(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'created_by', 'Customers_Numbers');
    }
    public function editor(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'last_update_user', 'Customers_Numbers');
    }
    
    public function comments(): HasMany
    {
        return $this->hasMany(ArticleComment::class, 'article_id', 'id_article');
    }

    protected static function booted(): void
    {
        static::creating(function (Article $article) {
            $article->created_by = auth()->user()->customer_number ?? 'WebUser';
            $article->creation_date = now();
        });
        
        static::updating(function (Article $article) {
            $article->last_update_date = now();
            $article->last_update_user = auth()->user()->customer_number ?? 'WebUser';
        });
    }

}