<?php

namespace Levu42\RegExpander\Elements;

use InvalidArgumentException;

class Characters extends Element
{
    private const az = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const digits = '0123456789';
    private const extra = 'ÄÖÜëïäöüëïáéíóúâêîôûàèìòùþÞðÐæÆ!"#$%&/()=-\\}][{?\'~*+`´^.:,;<>|';
    private const underscore = '_';
    private const whitespace = " \n\r\t";
    
    public function __construct(
        private string $characters
    ) {}

    public function generate(): string
    {
        $length = mb_strlen($this->characters);
        return mb_substr($this->characters, rand(0, $length - 1), 1);
    }

    public static function fromClass(string $which): self
    {
        return new self(match($which) {
            'd' => self::digits,
            'D' => self::az . self::extra . self::underscore . self::whitespace,
            'w' => self::az . self::digits . self::underscore,
            'W' => self::extra . self::whitespace,
            's' => self::whitespace,
            'S' => self::az . self::digits . self::extra . self::underscore,
            default => throw new InvalidArgumentException("$which is not a recognized character class")
        });
    }

    public static function fromDot(): self
    {
        return new self(self::az . self::digits . self::extra . self::underscore . self::whitespace);
    }

    public static function fromPattern(string $pattern): self
    {
        //TODO ranges
        return new self($pattern);
    }
}
