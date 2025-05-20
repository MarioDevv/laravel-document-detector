<?php

namespace MarioDevv\LaravelDocumentDetector\services;

use Google\ApiCore\ApiException;
use Google\Cloud\DocumentAI\V1\Client\DocumentProcessorServiceClient;

class DocumentAIRegionDetector
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
