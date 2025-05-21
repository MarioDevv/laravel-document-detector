<?php

namespace MarioDevv\LaravelDocumentDetector\Tests\Unit;


use MarioDevv\LaravelDocumentDetector\Services\RegexFieldParser;
use PHPUnit\Framework\TestCase;

class RegexFieldParserTest extends TestCase
{
    public function testParseExtractsFields()
    {
        $parser = new RegexFieldParser();
        $text   = "Name: John Doe
                   Document No: 123456
                   Date of Birth: 01/01/1990
                   ";

        $fields = $parser->parse($text);
        $this->assertEquals('John Doe', $fields['name']);
        $this->assertEquals('123456', $fields['number']);
        $this->assertEquals('01/01/1990', $fields['dob']);
    }
}
