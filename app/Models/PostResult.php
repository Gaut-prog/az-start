<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostResult extends Model
{
    protected $primaryKey = 'id_result';
    protected $table = 'marketing_posts_results';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'id_post',
        'collected_date',
        'collected_by_employee_az',
        'nb_like',
        'nb_view',
        'nb_comment',
        'nb_message',
        'nb_call',
        'nb_follower',
        'nb_prospect',
        'nb_customer',
        'note',
    ];
    
    protected $casts = [
        'collected_date' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (PostResult $result) {
            $result->collected_by_employee_az = auth()->user()->customer_number ?? 'WebUser';
            $result->collected_date = now();
        });
    }

    public function post() : BelongsTo
    {
        return $this->belongsTo(PostTarget::class, 'id_post', 'id_post');
    }
 
    public function author(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'collected_by_employee_az', 'Customers_Numbers');
    }
}