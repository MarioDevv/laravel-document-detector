<?php

namespace MarioDevv\LaravelDocumentDetector\services;

use Intervention\Image\ImageManager;
use MarioDevv\LaravelDocumentDetector\Contracts\PreprocessorInterface;

class ImagePreprocessor implements PreprocessorInterface
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(['driver' => 'imagick']);
    }

    public function preprocess(string $path): Image
    {
        $img = $this->manager->make($path)
            ->resize(null, 2000, fn($c) => $c->aspectRatio())
            ->greyscale()
            ->brightness(-10)
            ->contrast(20)
            ->rotate(-$this->detectSkew($path));

        return $img;
    }

    private function detectSkew(string $path): float
    {
        // TODO: Implement skew detection (e.g. via Hough transform or OCR orientation)
        return 0.0;
    }
}
