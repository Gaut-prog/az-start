<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseType extends Model
{
    protected $primaryKey = 'id_types';
    protected $table = 'expenses_types';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'Names',
        'Descriptions',
    ];

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'Types', 'id_types');
    }
}