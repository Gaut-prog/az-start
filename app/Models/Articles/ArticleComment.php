<?php

namespace App\Models\Articles;

use App\Models\Article;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleComment extends Model
{
    use HasFactory;

    protected $table = 'website_articles_comments';
    protected $primaryKey = 'id_comment';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'Date',
        'article_id',
        'author',
        'comment',
        'en_comment',
        'creation_date',
        'status',
    ];

    protected $casts = [
        "creation_date" => 'datetime',
    ];

    public function getContentAttribute(): ?string
    {
        return app()->getLocale() === 'fr' ? $this->comment : $this->en_comment;
    }

    public function article() : BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id', 'id_article');
    }

    public function user()
    {
        return $this->belongsTo(Customer::class, 'author', 'Customers_Numbers');
    }

     protected static function booted(): void
    {
        static::creating(function (ArticleComment $comment) {
            $comment->author = auth()->user()->customer_number ?? 'WebUser';
            $comment->creation_date = now();
            $comment->status = 1;
        });
    }
}