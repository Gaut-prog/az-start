<?php

namespace App\Enums;

enum ArticleClickType
{
    case description_click;
    case document_click;
    case link_click;
    case share_click;

    public function trans() : string
    {
        $version = app()->getLocale();
        
        return match($this){
            self::description_click => $version == 'fr' ? "Description" : "Description",
            self::document_click => $version == 'fr' ? "Document" : "Document",
            self::link_click => $version == 'fr' ? "Lien" : "Link",
            self::share_click => $version == 'fr' ? "Partage" : "Share",
        };
    }
}