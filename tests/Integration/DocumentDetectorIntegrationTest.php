<?php


namespace MarioDevv\LaravelDocumentDetector\Tests\Integration;


use Dotenv\Dotenv;
use MarioDevv\LaravelDocumentDetector\Services\OpenAIOcrService;
use PHPUnit\Framework\TestCase;
use MarioDevv\LaravelDocumentDetector\DocumentDetector;
use MarioDevv\LaravelDocumentDetector\Services\ImagePreprocessor;
use MarioDevv\LaravelDocumentDetector\Services\ImageEnhancer;

class DocumentDetectorIntegrationTest extends TestCase
{

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        Dotenv::createImmutable(__DIR__ . '/../../')->load();
    }


    public function testScanPipeline()
    {
        $detector = new DocumentDetector(
            new ImagePreprocessor(),
            new ImageEnhancer(),
            new OpenAIOcrService(env('OPEN_AI_API_KEY'), 'gpt-4o'),
        );

        $result = $detector->scan(__DIR__ . '/../../documents/de/german2.png');

        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('idCardNumber', $result);
        $this->assertArrayHasKey('countryISO', $result);
    }
}
