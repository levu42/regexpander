<?php

namespace Levu42\RegExpander\Elements;

use RuntimeException;

class Expression extends Element
{
    public function __construct(
        private array $elements
    ) {
    }

    public function generate(): string
    {
        $return = '';
        foreach ($this->elements as $element) {
            $return .= $element->generate();
        }

        return $return;
    }

    public static function fromPattern(string $pattern): self
    {
        $elements = self::parse($pattern);

        return new self($elements);
    }

    public static function parse(string $pattern): array
    {
        $i = 0;
        $length = mb_strlen($pattern);
        $alternativesStack = [];
        $stack = [];
        $latest = null;
        $memory = '';

        $push = function (Element $e) use (&$stack, &$latest, &$memory) {
            $stack[] = $e;
            $latest = $e;
            $memory = '';
        };
        $assert = function (bool $expression, string $error) {
            if (! $expression) {
                throw new RuntimeException($error);
            }
        };
        $replaceLast = function (Element $e) use (&$stack, &$latest, &$memory) {
            $stack[count($stack) - 1] = $e;
            $latest = $e;
            $memory = '';
        };
        $pushMemoryConstants = function () use (&$stack, &$latest, &$memory, $push) {
            if (strlen($memory)) {
                $constants = Constant::fromString($memory);
                foreach ($constants as $constant) {
                    $push($constant);
                }
            }
        };

        $bracket_stack = [];

        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($pattern, $i, 1);
            if (in_array('(', $bracket_stack)) {
                if ($char === '(') {
                    $bracket_stack[] = $char;
                } elseif ($char === ')') {
                    $assert(array_pop($bracket_stack) === match ($char) {
                        ')' => '(', ']' => '['
                    }, "$char didn't match the previously opened bracket type");
                    $push(Expression::fromPattern($memory));

                    continue;
                }
                $memory .= $char;
            } elseif ($char === '\\') {
                $assert(($i + 1) < $length, "Can't have \\ as the last character in an expression");
                if (! in_array('[', $bracket_stack)) {
                    $pushMemoryConstants();
                }
                $nextchar = mb_substr($pattern, $i + 1, 1);
                $i++;
                if (in_array($nextchar, ['s', 'S', 'd', 'D', 'w', 'W'])) {
                    if (! in_array('[', $bracket_stack)) {
                        $push(Characters::fromClass($nextchar));
                    } else {
                        $memory .= Characters::classToCharacters($nextchar);
                    }

                    continue;
                }
                $memory .= $nextchar;
            } elseif ($char === '.' && ! in_array('[', $bracket_stack)) {
                $pushMemoryConstants();
                $push(Characters::fromDot());
            } elseif (in_array($char, ['?', '*', '+']) && ! in_array('[', $bracket_stack)) {
                $pushMemoryConstants();
                $assert($latest instanceof Element, "Can't have $char at the start of an expression");
                /** @var Element $latest */
                $replaceLast(match ($char) {
                    '?' => Multiple::optional($latest),
                    '*' => Multiple::zeroOrMany($latest),
                    '+' => Multiple::oneOrMany($latest)
                });
            } elseif (in_array($char, ['[', '('])) {
                $pushMemoryConstants();
                $bracket_stack[] = $char;
            } elseif (in_array($char, [']', ')'])) {
                $assert(array_pop($bracket_stack) === match ($char) {
                    ')' => '(', ']' => '['
                }, "$char didn't match the previously opened bracket type");
                if ($char === ']') {
                    $push(Characters::fromPattern($memory));
                }
            } elseif ($char === '|' && ! in_array('[', $bracket_stack)) {
                $pushMemoryConstants();
                $alternativesStack[] = new Expression($stack);
                $stack = [];
                $latest = null;
                $memory = '';
            } elseif ($char === '{' && ! in_array('[', $bracket_stack)) {
                $assert(($i + 1) < $length, "Can't have { as the last character in an expression");
                $closing_index = mb_strpos($pattern, '}', $i + 1);
                $assert($closing_index !== false, "Opening { must be matched with closing }");
                $substr_len = $closing_index - $i - 1;
                $multiply_info = mb_substr($pattern, $i + 1, $substr_len);
                if (strpos($multiply_info, ',') === false) {
                    $parts = [$multiply_info, $multiply_info];
                } else {
                    $parts = explode(',', $multiply_info, 2);
                }
                if ($parts[0] === '') {
                    $parts[0] = 0;
                } else {
                    $assert(ctype_digit($parts[0]), "Multipliers ({...,...}) can only work with decimal integer values");
                    $parts[0] = (int) $parts[0];
                }
                if ($parts[1] === '') {
                    $parts[1] = max(10, $parts[0] + 5);
                } else {
                    $assert(ctype_digit($parts[1]), "Multipliers ({...,...}) can only work with decimal integer values");
                    $parts[1] = (int) $parts[1];
                }
                /** @var Element $latest */
                $multiple = new Multiple($latest, $parts[0], $parts[1]);
                $replaceLast($multiple);
                $i = $closing_index;
            } elseif (in_array('[', $bracket_stack) && ($char === '-')) {
                $assert(mb_strlen($memory) > 0, "Can't have - at the beginning of a character class");
                $assert(($i + 1) < $length, "Can't have - at the end of a character class");
                $assert(mb_substr($pattern, $i + 1, 1) !== ']', "Can't have - at the end of a character class");
                $start = mb_ord(mb_substr($memory, -1));
                $end = mb_ord(mb_substr($pattern, $i + 1, 1));
                for ($j = $start + 1; $j <= $end; $j++) {
                    $memory .= mb_chr($j);
                }
                $i++;
            } else {
                $memory .= $char;
            }
        }
        $pushMemoryConstants();

        if (count($alternativesStack) === 0) {
            return $stack;
        }
        $alternativesStack[] = new Expression($stack);

        return [new Alternative($alternativesStack)];
    }
}
