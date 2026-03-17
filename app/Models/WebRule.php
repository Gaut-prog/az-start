<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebRule extends Model
{
    use HasFactory;

    protected $table = 'web_rules';

    protected $primaryKey = 'id_rule';
    protected $connection = 'mysql2';


    public $timestamps = false;

    protected $fillable = [
        'rule_nb',
        'rule_category',
        'rule_text',
        'fr_rule_text'
    ];

    public function getTRuleTextAttribute(): string
    {
        return app()->getLocale() === 'fr' ? $this->fr_rule_text : $this->rule_text;
    }
}