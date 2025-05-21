<?php

namespace MarioDevv\LaravelDocumentDetector\Tests\Unit;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use MarioDevv\LaravelDocumentDetector\Services\VisionOcrService;
use Intervention\Image\ImageManager;

class VisionOcrServiceTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        Dotenv::createImmutable(__DIR__ . '/../../')->load();
    }

    public function testExtractTextReturnsDetectedString()
    {

        $manager = ImageManager::imagick();

        $img = $manager->read(__DIR__ . '/../../document.png');

        $service = new VisionOcrService();

        $text = $service->extractText($img);

        $this->assertIsString($text, 'extractText debe devolver un string');
    }

}
