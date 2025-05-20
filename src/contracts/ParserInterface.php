<?php

namespace MarioDevv\LaravelDocumentDetector\Contracts;

interface ParserInterface
{
    /**
     * @param string $text Full OCR text blob
     * @return array Extracted fields (e.g. name, number, date)
     */
    public function parse(string $text): array;
}
