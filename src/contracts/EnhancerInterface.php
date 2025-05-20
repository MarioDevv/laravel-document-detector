<?php

namespace MarioDevv\LaravelDocumentDetector\Contracts;

use Intervention\Image\Image;

interface EnhancerInterface
{
    public function enhance(Image $img): Image;
}
