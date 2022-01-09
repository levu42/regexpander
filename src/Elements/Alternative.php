<?php

namespace Levu42\RegExpander\Elements;

class Alternative extends Element
{
    public function __construct(
        private array $elements
    ) {}

    public function generate(): string
    {
        $element = $this->elements[rand(0, count($this->elements) -1)];
        /** @var Element $element */
        return $element->generate();
    }
}
