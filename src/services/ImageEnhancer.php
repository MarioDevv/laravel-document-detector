<?php

namespace MarioDevv\LaravelDocumentDetector\services;

use Intervention\Image\Image;
use MarioDevv\LaravelDocumentDetector\Contracts\EnhancerInterface;

class ImageEnhancer implements EnhancerInterface
{
    public function enhance(Image $img): Image
    {
        return $img->sharpen(1, 2)
            ->contrast(5);
    }
}
