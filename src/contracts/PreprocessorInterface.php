<?php

namespace MarioDevv\LaravelDocumentDetector\Contracts;

use Intervention\Image\Image;

interface PreprocessorInterface
{
    public function preprocess(string $path): Image;
}
