<?php

namespace App\Enums;

enum QuestionOptionType
{
    case radio;
    case multicheck;
    case select;
    case freetext;

    public function trans() : string
    {
        $version = app()->getLocale();
        
        return match($this){
            self::radio => $version == 'fr' ? "Choix unique" : "Unique choice",
            self::multicheck => $version == 'fr' ? "Choix multiple" : "Multiple choice",
            self::select => $version == 'fr' ? "Selection" : "Selectbox",
            self::freetext => $version == 'fr' ? "Texte" : "Text",
        };
    }
}