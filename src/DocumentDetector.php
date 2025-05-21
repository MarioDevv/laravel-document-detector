<?php

namespace MarioDevv\LaravelDocumentDetector;

use MarioDevv\LaravelDocumentDetector\Contracts\PreprocessorInterface;
use MarioDevv\LaravelDocumentDetector\Contracts\LangDetector;
use MarioDevv\LaravelDocumentDetector\Contracts\EnhancerInterface;
use MarioDevv\LaravelDocumentDetector\Contracts\OcrServiceInterface;
use MarioDevv\LaravelDocumentDetector\Contracts\ParserInterface;

class DocumentDetector
{
    public function __construct(
        protected PreprocessorInterface $preprocessor,
        protected EnhancerInterface     $enhancer,
        protected OcrServiceInterface   $ocrService,
        protected ParserInterface       $parser
    )
    {
    }

    public function scan(string $path): array
    {

        // Primero procesamos la imagen para clarificarla y optimizarla
        $img = $this->preprocessor->preprocess($path);

        // Mejoramos más aún la calidad de la imagen
        $enhancedImg = $this->enhancer->enhance($img);

        // Extraemos el texto de la imagen
        $text = $this->ocrService->extractText($enhancedImg);

        return $this->parser->parse($text);
    }
}
