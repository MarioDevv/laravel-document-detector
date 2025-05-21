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
        protected LangDetector          $regionDetector,
        protected EnhancerInterface     $enhancer,
        protected OcrServiceInterface   $ocrService,
        protected ParserInterface       $parser
    )
    {
    }

    public function scan(string $path): array
    {
        $stage1 = $this->preprocessor->preprocess($path);
        $stage2 = $this->regionDetector->detect($stage1);
        $stage3 = $this->enhancer->enhance($stage2);
        $text   = $this->ocrService->extractText($stage3);
        return $this->parser->parse($text);
    }
}
