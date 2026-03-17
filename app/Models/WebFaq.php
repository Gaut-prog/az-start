<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebFaq extends Model
{
    use HasFactory;

    protected $table = 'web_faq';
    protected $primaryKey = 'id_faq';
    protected $connection = 'mysql2';

    public $timestamps = false;

    protected $fillable = [
        'user_category',
        'question',
        'fr_question',
        'answer',
        'fr_answer',
    ];

    public function getTQuestionAttribute(): string
    {
        return app()->getLocale() === 'fr' ? $this->fr_question : $this->question;
    }

    public function getTAnswerAttribute(): string
    {
        return app()->getLocale() === 'fr' ? $this->fr_answer : $this->answer;
    }
}