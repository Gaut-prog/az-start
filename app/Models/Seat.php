<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $primaryKey = 'seat_id';
    protected $table = 'seats';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'nb_places',
        'description',
        'condition',
    ];
}