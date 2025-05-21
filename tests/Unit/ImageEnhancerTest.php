<?php

namespace MarioDevv\LaravelDocumentDetector\Tests\Unit;

use Intervention\Image\ImageManager;
use MarioDevv\LaravelDocumentDetector\Services\ImageEnhancer;
use PHPUnit\Framework\TestCase;

class ImageEnhancerTest extends TestCase
{
    public function testEnhanceSharpenAndContrast()
    {
        $manager = ImageManager::imagick();
        $fixture = __DIR__ . '/../../document.png';

        $imgOriginal = $manager->read($fixture);
        $imgForEnh   = $manager->read($fixture);

        // 3) Ejecutamos el enhancer
        $enhanced = (new ImageEnhancer())->enhance($imgForEnh);

        $tmpOrig = sys_get_temp_dir() . '/orig_' . uniqid() . '.png';
        $tmpNew  = sys_get_temp_dir() . '/new_' . uniqid() . '.png';

        $imgOriginal->save($tmpOrig);
        $enhanced->save($tmpNew);

        $blobOrig = file_get_contents($tmpOrig);
        $blobNew  = file_get_contents($tmpNew);

        @unlink($tmpOrig);
        @unlink($tmpNew);

        $this->assertNotEquals(
            $blobOrig,
            $blobNew,
            'La imagen procesada debe diferir del original al aplicar sharpen y contraste'
        );
    }
}
