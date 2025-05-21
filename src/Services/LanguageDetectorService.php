<?php
declare(strict_types=1);

namespace MarioDevv\LaravelDocumentDetector\Services;

use LanguageDetection\Language;
use MarioDevv\LaravelDocumentDetector\Contracts\LangDetector;

class LanguageDetectorService implements LangDetector
{

    private Language $detector;
    public function __construct(array $locales = ['es', 'en', 'de'])
    {
        $this->detector = new Language($locales);
    }

    public function detect(string $text): string
    {
        $bestScore = $this->detector->detect($text)->limit(0, 1)->close();
        return key($bestScore);
    }


}
