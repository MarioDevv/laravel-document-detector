<?php

namespace MarioDevv\LaravelDocumentDetector\Contracts;

use Intervention\Image\Image;

interface DocumentRegionDetectorInterface
{
    public function detect(Image $img): Image;
}
