<?php

namespace App\Models;

use App\Enums\PostedOn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostTarget extends Model
{
    protected $primaryKey = 'id_post';
    protected $table = 'marketing_posts_targets';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'post_title',
        'posting_date',
        'posted_by_employee_az',
        'posted_on',
        'page_id',
        'post_link',
        'sponsored',
        'sponsored_amount',
        'target_like',
        'target_view',
        'target_comment',
        'target_message',
        'target_call',
        'target_follower',
        'target_prospect',
        'target_customer',
    ];
    
    protected $casts = [
        'posting_date' => 'datetime',
        'posted_on' => PostedOn::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (PostTarget $target) {
            $target->posted_by_employee_az = auth()->user()->customer_number ?? 'WebUser';
        });
    }

    public function page() : BelongsTo
    {
        return $this->belongsTo(SocialPage::class, 'page_id', 'id');
    }
 
    public function author(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'posted_by_employee_az', 'Customers_Numbers');
    }
}