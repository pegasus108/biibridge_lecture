<?php
namespace App\Library;

class KeywordsParser
{
    static public function parseFromString($keywords)
    {
        $keywords = str_replace('　', ' ', $keywords);
        $keywords = explode(' ', $keywords);
        $keywords = array_filter($keywords);
        $keywords = array_values($keywords);
        return $keywords;
    }
}
