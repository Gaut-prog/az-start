<?php

namespace App\Enums;

enum GalleryFileType : string
{
    case photo = 'photo';
    case video = 'video';

    public function trans() : string
    {
        $version = app()->getLocale();
        
        return match($this){
            self::photo => $version == 'fr' ? "Photo" : "Picture",
            self::video => $version == 'fr' ? "Vidéo" : "Video",
        };
    }
}