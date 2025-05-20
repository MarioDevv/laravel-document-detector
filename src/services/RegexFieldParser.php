<?php

namespace MarioDevv\LaravelDocumentDetector\services;

use MarioDevv\LaravelDocumentDetector\Contracts\ParserInterface;

class RegexFieldParser implements ParserInterface
{
    public function parse(string $text): array
    {
        preg_match('/Name\s*[:\-]\s*(?<name>[A-Za-z ]+)/i', $text, $mName);
        preg_match('/Document\s*No\.?\s*[:\-]\s*(?<number>\w+)/i', $text, $mNum);
        preg_match('/Date\s*of\s*Birth\s*[:\-]\s*(?<dob>[\d\/\-]+)/i', $text, $mDob);

        return [
            'name'    => $mName['name'] ?? null,
            'number'  => $mNum['number'] ?? null,
            'dob'     => $mDob['dob'] ?? null,
            'rawText' => $text,
        ];
    }
}
