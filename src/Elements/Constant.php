<?php

namespace Levu42\RegExpander\Elements;

class Constant extends Element
{
    public function __construct(
        private string $constant
    ) {
    }

    public function generate(): string
    {
        return $this->constant;
    }

    public static function fromString(string $input): array
    {
        $return = [];
        for ($i = 0; $i < mb_strlen($input); $i++) {
            $return[] = new Constant(mb_substr($input, $i, 1));
        }

        return $return;
    }
}
