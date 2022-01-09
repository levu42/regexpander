<?php

use Levu42\RegExpander\Elements\Expression;

require_once("vendor/autoload.php");

if ($argc > 1) {
    print_r(Expression::parse($argv[1]));
    echo "\n\n";
    for ($i=0;$i<5;$i++) {
        echo Expression::fromPattern($argv[1])->generate() . "\n";
    }
    die;
}

$patterns = [
    'a',
    'abc',
    '\\d',
    'a.c',
    'a(b)c',
    'a|b',
    'abc|def',
    '(B|C)at',
    '[ABCDEF]+-\\d\\d',
    '\\d{3}-\\d{2,4}-\\d{,2}-\\d{8,}',
    '[a-z]',
    '[a-f]',
    '[a\\-f]',
    '[a-z\\d]',
    '[^a-z]',
];

foreach($patterns as $p) {
    echo "Testing $p...\n";
    var_dump(Expression::parse($p));
    var_dump(Expression::fromPattern($p)->generate());
    echo "\n";
}