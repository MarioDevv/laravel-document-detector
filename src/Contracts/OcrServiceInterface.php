<?php

namespace MarioDevv\LaravelDocumentDetector\Contracts;

use Intervention\Image\Image;

interface OcrServiceInterface
{
    public function extractData(Image $img): array;
}

