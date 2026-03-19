<?php

namespace App\Enums;

enum CompanySize
{
    case verysmall;
    case small;
    case medium;
    case big;
    case verysmall_and_small;
    case small_and_medium;
    case allsize;

    public function trans() : string
    {
        $version = app()->getLocale();
        
        return match($this){
            self::verysmall => $version == 'fr' ? "Très Petite Entreprise" : "Very Small Company",
            self::small => $version == 'fr' ? "Petite Entreprise" : "Small Company",
            self::medium => $version == 'fr' ? "Moyenne Entreprise" : "Medium Company",
            self::big => $version == 'fr' ? "Grande Entreprise" : "Big Company",
            self::verysmall_and_small => $version == 'fr' ? "Très Petite & Petite Entreprise" : "Very Small & Small Company",
            self::small_and_medium => $version == 'fr' ? "Petite & Moyenne Entreprise" : "Small & Medium Company",
            self::allsize => $version == 'fr' ? "Tout type d'Entreprise" : "All type of Company",
        };
    }
}