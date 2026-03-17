<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bug extends Model
{
    protected $table = 'bugs';
    protected $primaryKey = 'id_bugs';
    public $timestamps = false;

    protected $fillable = [
        'features',
        'impact',
        'descriptions',
        'reporters',
        'assignto',
        'report_date',
        'update_date',
        'note',
        'status',
        'document',
        'document_name',
    ];
}
