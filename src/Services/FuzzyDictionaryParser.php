<?php
namespace MarioDevv\LaravelDocumentDetector\Services;

use MarioDevv\LaravelDocumentDetector\Contracts\ParserInterface;
use MarioDevv\LaravelDocumentDetector\Contracts\LangDetector;

class FuzzyDictionaryParser implements ParserInterface
{
    public function __construct(
        private FieldDictionary $dict,
        private LangDetector    $langDet
    ){}

    public function parse(string $text): array
    {
        $lang  = $this->langDet->detect($text);
        $lines = preg_split('/\R/', $text);

        return [
            'surname' => $this->extractField($lines,'surname',$lang),
            'name'    => $this->extractField($lines,'name',   $lang),
            'number'  => $this->extractField($lines,'number', $lang),
        ];
    }

    private function extractField(array $lines,string $field,string $lang): string
    {
        $bestScore = 0;
        $bestValue = '';

        $labels = $this->dict->labels($field,$lang);

        foreach ($lines as $line) {
            $clean = trim(preg_replace('/\s+/', ' ', $line));
            foreach ($labels as $label) {
                // 1) Si etiqueta + espacio + dato en la misma línea
                if (preg_match('/'.preg_quote($label,'/').'\s*[:\-]?\s*(.+)$/iu',$clean,$m)) {
                    return trim($m[1]);
                }
                // 2) Si etiqueta sola, el dato puede venir en la siguiente línea
                if (stripos($clean,$label) !== false) {
                    $idx = array_search($line,$lines);
                    if (isset($lines[$idx+1])) {
                        return trim($lines[$idx+1]);
                    }
                }
                // 3) Fuzzy match sobre la línea, si es parecido a la etiqueta
                similar_text(
                    mb_strtolower($clean),
                    mb_strtolower($label),
                    $percent
                );
                if ($percent > 60 && $percent > $bestScore) {
                    // asumimos que todo lo que no sea la etiqueta es el valor
                    $value = trim(preg_replace(
                        '/'.preg_quote($label,'/').'/iu','',$clean
                    ), ':- ');
                    $bestScore = $percent;
                    $bestValue = $value;
                }
            }
        }

        return $bestValue;
    }
}
