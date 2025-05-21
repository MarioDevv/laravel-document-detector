<?php

namespace MarioDevv\LaravelDocumentDetector\Contracts;

interface LangDetector
{
    public function detect(string $text): string;
}
