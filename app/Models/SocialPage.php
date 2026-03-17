<?php

namespace App\Models;

use App\Action\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SocialPage extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'social_pages';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'url',
        'username',
        'password',
    ];
    
    public function posts() : HasMany
    {
        return $this->hasMany(PostTarget::class, 'page_id', 'id');
    }

    public function getDecryptUsernameAttribute() : string
    {
        if(!$this->username)
            return '';
        return Helpers::decryptData($this->username) ?? '';
    }
    
    public function getDecryptPasswordAttribute() : string
    {
        if(!$this->password)
            return '';
        return Helpers::decryptData($this->password) ?? '';
    }
    
    public function getPasswordSymbolAttribute() : string
    {
        $star = '';
        for($i = 0; $i <strlen($this->getDecryptPasswordAttribute()); $i++){
            $star .= '*';
        }
        return $star;
    }

    protected static function booted(): void
    {
        static::creating(function (SocialPage $page) {
            if($page->username)
                $page->username = Helpers::encryptData($page->username);
            if($page->password)
                $page->password = Helpers::encryptData($page->password);
        });
        
        static::updating(function (SocialPage $page) {
            if($page->username)
                $page->username = Helpers::encryptData($page->username);
            if($page->password)
                $page->password = Helpers::encryptData($page->password);
        });
    }
}