<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'created_date',
        'buyer_id',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'buyer_message',
        'customers',
        'order_number',
        'doc',
        'feedback_type',
        'comment',
        'related_to',
    ];
}