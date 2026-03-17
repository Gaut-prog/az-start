<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $table = 'web_about_us_team';

    protected $primaryKey = 'member_id';
    protected $connection = 'mysql2';

    protected $fillable = [
        'name',
        'role',
        'description',
        'fr_description',
        'photo',
        'facebook_link',
        'google_link',
        'twitter_link',
        'linkedin_link',
        'email',
        'phone',
    ];

    protected $hidden = ['photo'];

    public $timestamps = false;

    public function getTDescriptionAttribute(): ?string
    {
        return app()->getLocale() == 'fr' ? $this->fr_description : $this->description;
    }

    public function getFPhotoAttribute(): ?string
    {
        return $this->photo ? 'data:image/jpeg;base64,'.base64_encode($this->photo) : '';
    }
}