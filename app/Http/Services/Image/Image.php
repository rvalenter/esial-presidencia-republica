<?php

namespace App\Http\Services\Image;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class Image
{
    public static function resize($imagePath, $content = false)
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($content ? $imagePath : file_get_contents($imagePath));
        $image->cover(300, 310);

        return $image->toPng();
    }
}
