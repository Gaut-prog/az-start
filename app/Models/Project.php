<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $primaryKey = 'id_project';
    protected $table = 'companyprojects';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'project_name',
        'project_description',
    ];

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'linked_project', 'id_project');
    }
}