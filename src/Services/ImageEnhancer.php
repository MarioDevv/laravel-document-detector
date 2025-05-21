<?php

namespace MarioDevv\LaravelDocumentDetector\Services;

use Intervention\Image\Image;
use MarioDevv\LaravelDocumentDetector\Contracts\EnhancerInterface;

class ImageEnhancer implements EnhancerInterface
{
    public function enhance(Image $img): Image
    {
        return $img->sharpen(1)
            ->contrast(5);
    }
}
