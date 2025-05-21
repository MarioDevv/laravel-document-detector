<?php


namespace MarioDevv\LaravelDocumentDetector\Tests\Integration;


use MarioDevv\LaravelDocumentDetector\Services\FieldDictionary;
use MarioDevv\LaravelDocumentDetector\Services\FuzzyDictionaryParser;
use PHPUnit\Framework\TestCase;
use MarioDevv\LaravelDocumentDetector\DocumentDetector;
use MarioDevv\LaravelDocumentDetector\Services\ImagePreprocessor;
use MarioDevv\LaravelDocumentDetector\Services\LanguageDetectorService;
use MarioDevv\LaravelDocumentDetector\Services\ImageEnhancer;
use MarioDevv\LaravelDocumentDetector\Services\VisionOcrService;

class DocumentDetectorIntegrationTest extends TestCase
{
    public function testScanPipeline()
    {
        $detector = new DocumentDetector(
            new ImagePreprocessor(),
            new ImageEnhancer(),
            new VisionOcrService(),
            new FuzzyDictionaryParser(
                new FieldDictionary(require __DIR__ . '/../../src/config/dictionary.php'),
                new LanguageDetectorService()
            ),
        );

        $result = $detector->scan(__DIR__ . '/../../documents/german.png');
        var_dump($result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('surname', $result);
        $this->assertArrayHasKey('number', $result);
    }
}
