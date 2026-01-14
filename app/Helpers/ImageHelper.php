<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Get image URL with fallback
     */
    public static function getImageUrl($imagePath)
    {
        if ($imagePath && file_exists(storage_path('app/public/' . $imagePath))) {
            return asset('storage/' . $imagePath);
        }
        
        return null;
    }

    /**
     * Check if image exists
     */
    public static function imageExists($imagePath)
    {
        if (!$imagePath) {
            return false;
        }
        
        return file_exists(storage_path('app/public/' . $imagePath));
    }
}
