<?php

use Levu42\RegExpander\Elements\Expression;

require_once("vendor/autoload.php");

$patterns = [
    'a',
    'abc',
    '\\d',
    'a.c',
    'a(b)c',
    'a|b',
    'abc|def',
    '(B|C)at',
    '[ABCDEF]+-\d\d',
    '\d{3}-\d{2,4}-\d{,2}-\d{8,}'
];

foreach($patterns as $p) {
    echo "Testing $p...\n";
    var_dump(Expression::parse($p));
    var_dump(Expression::fromPattern($p)->generate());
    echo "\n";
}