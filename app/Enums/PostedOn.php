<?php

namespace App\Enums;

enum PostedOn : string
{
    case facebook = "facebook";
    case linkedin = "linkedin";
    case instagram = "instagram";
    case twitter = "twitter";
    case google = "google";
    case az = "az-companies.com";
    case youtube = "youtude";
    case other = "other";

    public function trans() : string
    {
        $version = app()->getLocale();
        
        return match($this){
            self::facebook => $version == 'fr' ? "Facebook" : "Facebook",
            self::linkedin => $version == 'fr' ? "Linkedin" : "Linkedin",
            self::instagram => $version == 'fr' ? "Instagram" : "Instagram",
            self::twitter => $version == 'fr' ? "Twitter" : "Twitter",
            self::google => $version == 'fr' ? "Google" : "Google",
            self::az => "az-companies.com",
            self::youtube => "Youtude",
            self::other => $version == 'fr' ? "Autre" : "Other",
        };
    }
}