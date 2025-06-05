<?php

namespace MarioDevv\LaravelDocumentDetector\Services;

use Intervention\Image\Image;
use MarioDevv\LaravelDocumentDetector\Contracts\OcrServiceInterface;
use OpenAI\Client as OpenAIClient;

class OpenAIOcrService implements OcrServiceInterface
{

    private OpenAIClient $client;

    public function __construct(
        private string $apiKey,
        private string $model,
    )
    {
        $this->client = \OpenAI::client($this->apiKey);
    }

    public
    function extractData(Image $img): array
    {

        // 3) Preparo mensajes: system con prompt + user con la imagen
        $messages = [
            [
                'role'    => 'system',
                'content' => <<<EOD
                                 Eres un extractor de datos de pasaporte o DNI/NIF. Recibirás una sola imagen. Extrae y valida únicamente estos campos:
                                 
                                 - "name": Nombre(s), exacto(s), con tildes y sin espacios extra.
                                 - "idCardNumber": Número de identificación.
                                 - "countryISO": Código ISO 3166-1 alpha-2 del país.
                                 
                                 Requisitos:
                                 
                                 1. Limpia y normaliza el texto:
                                    - Quita espacios iniciales/finales.
                                    - Unifica saltos de línea y tabulaciones.
                                    - Corrige errores comunes de OCR como “O” por “0” en números.
                                 
                                 2. Si un campo no puede leerse con confianza, asigna `null`. No inventes.
                                 
                                 3. Devuelve un **único JSON** con claves en minúsculas y en este orden:
                                    `name`, `idCardNumber`, `countryISO`.
                                 
                                 Ejemplo:
                                 {"name":"María Luisa","idCardNumber":"X1234567L","countryISO":"ES"}
                                 EOD
            ],
            ['role'    => 'user',
             'content' => [
                 [
                     'type'      => 'image_url',
                     'image_url' => ['url' => $img->encode()->toDataUri()],
                 ],
             ],
            ],
        ];

        // 4) Llamada al API con gpt-4o
        $response = $this->client->chat()->create([
            'model'       => $this->model,
            'messages'    => $messages,
            'temperature' => 0,
        ]);


        // 6) Devuelvo el JSON parseado
        $content = $response['choices'][0]['message']['content'] ?? '';

        // 7) Eliminar posibles fences de markdown
        $content = preg_replace('/^```(?:json)?\s*/', '', $content);
        $content = preg_replace('/\s*```$/', '', $content);

        return json_decode($content, true);
    }
}
