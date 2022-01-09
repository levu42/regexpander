<?php

namespace Levu42\RegExpander\Elements;

use InvalidArgumentException;
use RuntimeException;

class Characters extends Element
{
    private const az = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const digits = '0123456789';
    private const extra = 'ÄÖÜëïäöüëïáéíóúâêîôûàèìòùþÞðÐæÆ!"#$%&/()=-\\}][{?\'~*+`´^.:,;<>|@€';
    private const underscore = '_';
    private const whitespace = " \n\r\t";

    public function __construct(
        private string $characters
    ) {
    }

    public function generate(): string
    {
        $length = mb_strlen($this->characters);

        return mb_substr($this->characters, rand(0, $length - 1), 1);
    }

    public static function fromClass(string $which): self
    {
        return new self(self::classToCharacters($which));
    }

    public static function classToCharacters(string $which): string
    {
        return match ($which) {
            'd' => self::digits,
            'D' => self::az . self::extra . self::underscore . self::whitespace,
            'w' => self::az . self::digits . self::underscore,
            'W' => self::extra . self::whitespace,
            's' => self::whitespace,
            'S' => self::az . self::digits . self::extra . self::underscore,
            default => throw new InvalidArgumentException("$which is not a recognized character class")
        };
    }

    public static function fromDot(): self
    {
        return new self(self::az . self::digits . self::extra . self::underscore . self::whitespace);
    }

    public static function fromPattern(string $pattern): self
    {
        if (mb_substr($pattern, 0, 1) !== '^') {
            return new self($pattern);
        }

        $remaining = self::az . self::digits . self::extra . self::underscore . self::whitespace;
        $excluded = mb_substr($pattern, 1);
        for ($i = 0; $i < mb_strlen($excluded); $i++) {
            $remaining = str_replace(mb_substr($excluded, $i, 1), '', $remaining);
        }
        if (strlen($remaining) === 0) {
            throw new RuntimeException("You hit the limits of this library! You excluded with your character class so many characters that we don't have any left to use. Consider a pull request.");
        }

        return new self($remaining);
    }
}
