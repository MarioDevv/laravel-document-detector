<?php
// tests/Unit/DocumentAIRegionDetectorIntegrationTest.php

namespace MarioDevv\LaravelDocumentDetector\Tests\Unit;

use PHPUnit\Framework\TestCase;
use MarioDevv\LaravelDocumentDetector\Services\LanguageDetectorService;

class LanguageDetectorServiceTest extends TestCase
{

    private LanguageDetectorService $languageDetector;


    protected function setUp(): void
    {
        parent::setUp();
        $this->languageDetector = new LanguageDetectorService();
    }

    public function testDetectIntegrationReturnsSameImageInstance()
    {
        $sampleText = "Esto es un texto en español";

        $expectedCode = 'es';

        $langCode = $this->languageDetector->detect($sampleText);

        $this->assertEquals($expectedCode, $langCode);

    }
}
