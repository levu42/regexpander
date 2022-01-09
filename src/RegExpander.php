<?php

namespace Levu42\RegExpander;

use Levu42\RegExpander\Elements\Expression;

class RegExpander
{
    public static function generate(string $pattern): string
    {
        $expression = new Expression($pattern);
        return $expression->generate();
    }
}
