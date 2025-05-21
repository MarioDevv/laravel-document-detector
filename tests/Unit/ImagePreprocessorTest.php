<?php

namespace MarioDevv\LaravelDocumentDetector\Tests\Unit;

use MarioDevv\LaravelDocumentDetector\Services\ImagePreprocessor;
use PHPUnit\Framework\TestCase;

class ImagePreprocessorTest extends TestCase
{
    public function testPreprocessResizesToHeight2000AndReturnsImageInstance()
    {
        $fixture = __DIR__ . '/../../documents/es/spanish.png';

        $pre = new ImagePreprocessor();

        $result = $pre->preprocess($fixture);

        $this->assertEquals(
            2000,
            $result->height(),
            'La altura de la imagen procesada debe ser exactamente 2000px'
        );
    }
}
