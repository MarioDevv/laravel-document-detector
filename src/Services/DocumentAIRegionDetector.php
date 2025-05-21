<?php

namespace MarioDevv\LaravelDocumentDetector\Services;

use Google\ApiCore\ApiException;
use Google\Cloud\DocumentAI\V1\Client\DocumentProcessorServiceClient;
use Intervention\Image\Image;
use MarioDevv\LaravelDocumentDetector\Contracts\DocumentRegionDetectorInterface;

class DocumentAIRegionDetector implements DocumentRegionDetectorInterface
{
    protected DocumentProcessorServiceClient $docAI;
    protected string                         $processorName;

    public function __construct(string $processorName)
    {
        $this->docAI         = new DocumentProcessorServiceClient();
        $this->processorName = $processorName;
    }

    /**
     * @throws ApiException
     */
    public function detect(Image $img): Image
    {
        $content  = $img->encode('png')->getEncoded();
        $response = $this->docAI->processDocument([
            'name'        => $this->processorName,
            'rawDocument' => [
                'content'  => $content,
                'mimeType' => 'image/png',
            ],
        ]);

        $document = $response->getDocument();
        // TODO: Parse $document->getPages()[0]->getLayout()->getBoundingPoly() to crop
        // For now, return original
        return $img;
    }
}
