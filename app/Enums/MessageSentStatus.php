<?php

namespace App\Enums;

enum MessageSentStatus
{
    case all_received;
    case partially_received;
    case not_received;

    public function trans() : string
    {
        $version = app()->getLocale();
        
        return match($this){
            self::all_received => $version == 'fr' ? "Tout réçu" : "All received",
            self::partially_received => $version == 'fr' ? "Partiellement réçu" : "Partially received",
            self::not_received => $version == 'fr' ? "Aucun destinaire n'a réçu" : "No recipient received",
        };
    }
}