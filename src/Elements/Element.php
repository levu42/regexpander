<?php

namespace Levu42\RegExpander\Elements;

abstract class Element
{
    abstract public function generate(): string;
}
