<?php

namespace MarioDevv\LaravelDocumentDetector\Services;

use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Feature;
use Intervention\Image\Image;
use MarioDevv\LaravelDocumentDetector\Contracts\OcrServiceInterface;
use Google\Cloud\Vision\V1\Image as GoogleImage;

class VisionOcrService implements OcrServiceInterface
{
    protected ImageAnnotatorClient $vision;

    /** @throws ValidationException */
    public function __construct()
    {
        $this->vision = new ImageAnnotatorClient([
            'credentials' => __DIR__ . '/../../ocr_credentials.json',
            'transport'   => 'rest',
        ]);
    }

    /**
     * @throws ApiException
     */
    public function extractText(Image $img): string
    {
        // 1) Creamos un fichero temporal .jpg
        $tmpPath = tempnam(sys_get_temp_dir(), 'ocr') . '.jpg';

        // 2) Guardamos la imagen en ese fichero como JPEG
        //    (Intervention detecta extensión y usa JpegEncoder)
        $img->save($tmpPath);

        // 3) Leemos los bytes resultantes
        $content = file_get_contents($tmpPath);
        @unlink($tmpPath);

        // 4) Construye el objeto Image de la API
        $visionImage = (new GoogleImage())->setContent($content);

        // 5) Define la feature que queremos
        $feature = (new Feature())
            ->setType(Feature\Type::DOCUMENT_TEXT_DETECTION);

        // 6) Crea la petición de anotación
        $annotReq = (new AnnotateImageRequest())
            ->setImage($visionImage)
            ->setFeatures([$feature]);

        // 7) Empaqueta en batch
        $batchReq = (new BatchAnnotateImagesRequest())
            ->setRequests([$annotReq]);

        // 8) Lanza la llamada
        $batchResp = $this->vision->batchAnnotateImages($batchReq);

        // 9) Extrae la anotación de texto completo
        $responses = $batchResp->getResponses();
        if (empty($responses) || !$responses[0]->hasFullTextAnnotation()) {
            return '';
        }

        return $responses[0]
            ->getFullTextAnnotation()
            ->getText() ?: '';
    }
}
