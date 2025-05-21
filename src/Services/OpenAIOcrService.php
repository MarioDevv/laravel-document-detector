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
                                 Eres un extractor de datos de pasaporte o DNI/NIF. Recibirás solo una imagen. Extrae y valida:
                                 
                                 1. Campos (exactos, con tildes y sin espacios extra):
                                    - "name": Nombre(s).
                                    - "surname": Apellidos.
                                    - "id_number": Número de identificación.
                                 
                                 2. Limpia y normaliza:
                                    - Quita espacios al inicio/final.
                                    - Unifica saltos de línea y tabulaciones.
                                    - Corrige errores comunes de OCR (p.ej. “O”→“0” junto a dígitos).
                                 
                                 3. Si un campo no es legible con confianza, asígnale `null`. No inventes datos.
                                 
                                 4. Salida **únicamente** un JSON con claves en minúsculas y en este orden: name, surname, id_number.
                                 
                                 Ejemplo:
                                 {"name":"María Luisa","surname":"Gómez Fernández","id_number":"X1234567L"}
                                 EOD
            ],
            [
                'role'    => 'user',
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
