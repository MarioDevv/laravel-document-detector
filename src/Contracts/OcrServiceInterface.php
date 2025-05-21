<?php

namespace MarioDevv\LaravelDocumentDetector\Contracts;

use Intervention\Image\Image;

interface OcrServiceInterface
{
    /**
     * Devuelve todo el texto detectado en la imagen
     *
     * @param Image $img
     * @return string
     */
    public function extractText(Image $img): string;
}

