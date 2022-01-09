<?php

namespace Levu42\RegExpander\Elements;

class Multiple extends Element
{
    public function __construct(
        private Element $element,
        private int $min,
        private int $max
    ) {}

    public function generate(): string
    {
        $number = rand($this->min, $this->max);
        $return = '';
        for ($i = 0; $i < $number; $i++) {
            $return .= $this->element->generate();
        }
        return $return;
    }

    public static function optional(Element $element): self
    {
        return new self($element, 0, 1);
    }

    public static function oneOrMany(Element $element): self
    {
        return new self($element, 1, 10);
    }

    public static function zeroOrMany(Element $element): self
    {
        return new self($element, 0, 10);
    }
}
