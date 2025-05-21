<?php
namespace MarioDevv\LaravelDocumentDetector\Services;

class FieldDictionary
{
    private array $dict;
    public function __construct(array $dict)
    {
        $this->dict = $dict;
    }

    /**
     * Devuelve todas las etiquetas conocidas para $field en $langCode,
     * o todas si no existe $langCode.
     */
    public function labels(string $field, string $langCode): array
    {
        if (isset($this->dict[$field][$langCode])) {
            return $this->dict[$field][$langCode];
        }
        // fallback: todas las etiquetas de todos los idiomas
        return array_merge(...array_values($this->dict[$field]));
    }
}
