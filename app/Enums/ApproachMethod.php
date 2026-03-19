<?php

namespace App\Enums;

enum ApproachMethod
{
    case on_appointment;
    case on_call;
    case spontaneously;

    public function trans() : string
    {
        $version = app()->getLocale();
        
        return match($this){
            self::on_appointment => $version == 'fr' ? "Rendez-vous" : "On appointment",
            self::on_call => $version == 'fr' ? "Appel téléphonique" : "On call",
            self::spontaneously => $version == 'fr' ? "Spontanément" : "Spontaneously",
        };
    }
}