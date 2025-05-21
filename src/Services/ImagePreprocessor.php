<?php

namespace MarioDevv\LaravelDocumentDetector\Services;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use MarioDevv\LaravelDocumentDetector\Contracts\PreprocessorInterface;

class ImagePreprocessor implements PreprocessorInterface
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = ImageManager::imagick();
    }

    public function preprocess(string $path): Image
    {
        return $this->manager
            ->read($path)
            ->resize(null, 2000)
            ->greyscale()
            ->brightness(-10)
            ->contrast(20)
            ->rotate(-$this->detectSkew($path));
    }

    private function detectSkew(string $path): float
    {
        // TODO: Implement skew detection (e.g. via Hough transform or OCR orientation)
        return 0.0;
    }
}
