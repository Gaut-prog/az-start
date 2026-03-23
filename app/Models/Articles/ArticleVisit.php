<?php

namespace App\Models\Articles;

use App\Enums\ArticleClickType;
use App\Models\Article;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleVisit extends Model
{
    use HasFactory;

    protected $table = 'website_articles_visite_info';
    protected $primaryKey = 'id';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'ip_address',
        'Country',
        'article_id',
        'click_type',
        'Date',
        'user',
    ];

    protected $casts = [
        "Date" => 'datetime',
        "click_type" => ArticleClickType::class,
    ];

    public function article() : BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id', 'id_article');
    }

    public function author()
    {
        return $this->belongsTo(Customer::class, 'user', 'Customers_Numbers');
    }

     protected static function booted(): void
    {
        static::creating(function (ArticleComment $comment) {
            $comment->user = auth()->user()->customer_number ?? 'WebUser';
            $comment->Date = now();
        });
    }
}