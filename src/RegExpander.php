<?php

namespace Levu42\RegExpander;

use Levu42\RegExpander\Elements\Expression;

class RegExpander
{
    public static function generate(string $pattern): string
    {
        $expression = Expression::fromPattern($pattern);

        return $expression->generate();
    }
}
