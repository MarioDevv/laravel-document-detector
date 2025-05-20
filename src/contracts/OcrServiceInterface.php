<?php

namespace MarioDevv\LaravelDocumentDetector\Contracts;

use Intervention\Image\Image;

interface OcrServiceInterface
{
    /**
     * @return array Parsed annotation structure
     */
    public function extractText(Image $img): array;
}

