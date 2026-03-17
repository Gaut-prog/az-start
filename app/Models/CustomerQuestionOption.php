<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerQuestionOption extends Model
{
    protected $primaryKey = 'id_option';
    protected $table = 'customer_category_questions_options';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'id_question',
        'question_option',
        'en_question_option',
    ];

    public function getOptionAttribute() : string
    {
        return app()->getLocale() == 'fr' ? $this->question_option : $this->en_question_option;
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(CustomerQuestion::class, 'id_question', 'id_question');
    }
}