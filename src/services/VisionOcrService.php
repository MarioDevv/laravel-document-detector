<?php

namespace MarioDevv\LaravelDocumentDetector\services;

use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Feature;
use Intervention\Image\Image;

class VisionOcrService
{
    protected ImageAnnotatorClient $vision;

    public function __construct()
    {
        $this->vision = new ImageAnnotatorClient();
    }

    public function extractText(Image $img): array
    {
        $payload = $img->encode('jpg')->getEncoded();
        $requests = [
            [
                'image' => ['content' => $payload],
                'features' => [['type' => Feature::DOCUMENT_TEXT_DETECTION]],
            ]
        ];

        $response = $this->vision->batchAnnotateImages(['requests' => $requests]);
        $annotResponses = $response->getResponses();
        $fullText = $annotResponses[0]->getFullTextAnnotation();

        return $fullText ? $fullText->getText() : '';
    }

}
