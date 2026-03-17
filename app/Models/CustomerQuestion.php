<?php

namespace App\Models;

use App\Enums\QuestionOptionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerQuestion extends Model
{
    protected $primaryKey = 'id_question';
    protected $table = 'customer_category_questions';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'id_category',
        'question_title',
        'en_question_title',
        'question_option_type',
    ];

    protected $casts = [
       'question_option_type' => QuestionOptionType::class 
    ];

    public function getTitleAttribute() : string
    {
        return app()->getLocale() == 'fr' ? $this->question_title : $this->en_question_title;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CompanyCustomerCategory::class, 'id_category', 'Category_id');
    }

    public function options() : HasMany
    {
        return $this->hasMany(CustomerQuestionOption::class, 'id_question', 'id_question');
    }
}