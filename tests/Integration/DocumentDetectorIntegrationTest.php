<?php


namespace MarioDevv\LaravelDocumentDetector\Tests\Integration;


use PHPUnit\Framework\TestCase;
use MarioDevv\LaravelDocumentDetector\DocumentDetector;
use MarioDevv\LaravelDocumentDetector\Services\ImagePreprocessor;
use MarioDevv\LaravelDocumentDetector\Services\DocumentAIRegionDetector;
use MarioDevv\LaravelDocumentDetector\Services\ImageEnhancer;
use MarioDevv\LaravelDocumentDetector\Services\VisionOcrService;
use MarioDevv\LaravelDocumentDetector\Services\RegexFieldParser;

class DocumentDetectorIntegrationTest extends TestCase
{
    public function testScanPipeline()
    {
        $detector = new DocumentDetector(
            new ImagePreprocessor(),
            new DocumentAIRegionDetector('projects/PROJECT/locations/us/processors/processor-id'),
            new ImageEnhancer(),
            new VisionOcrService(),
            new RegexFieldParser()
        );

        $result = $detector->scan(__DIR__ . '/../fixtures/test-document.png');
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('number', $result);
        $this->assertArrayHasKey('dob', $result);
    }
}
