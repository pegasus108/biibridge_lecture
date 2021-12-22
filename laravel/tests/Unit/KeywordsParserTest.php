<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Library\KeywordsParser;

class KeywordsParserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_parseFromString()
    {
        $test_keywords = '　 test テスト　テスト-開発 　';
        $keywords = KeywordsParser::parseFromString($test_keywords);
        $this->assertCount(3, $keywords);
        $this->assertEquals('test', $keywords[0]);
        $this->assertEquals('テスト', $keywords[1]);
        $this->assertEquals('テスト-開発', $keywords[2]);
    }
}
