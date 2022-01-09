# PHP Regular Expression Expander

[![Latest Version on Packagist](https://img.shields.io/packagist/v/levu42/regexpander.svg?style=flat-square)](https://packagist.org/packages/levu42/regexpander)
[![Tests](https://github.com/levu42/regexpander/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/levu42/regexpander/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/levu42/regexpander.svg?style=flat-square)](https://packagist.org/packages/levu42/regexpander)

PHP library that expands a regular expression into a random string that matches this pattern

## Installation

You can install the package via composer:

```bash
composer require levu42/regexpander
```

## Usage

```php
$phoneNumber = Levu42\RegExpander::generate('\d{3}-\d{4}-\d{3}');
echo $phoneNumber;
```
## Supported regexes
* Character classes: \d, \D, \s, \S, \w, \W, .
  * \d = [0-9]
  * \D = [^0-9]
  * \s = [ \n\r]
  * \S = [^ \n\r]
  * \w = [a-zA-Z0-9_]
  * \W = [^a-zA-Z0-9_]
  * . = [anything]
* Custom character classes, e.g. [0-9a-f], [^x]
* Alternatives: a|b
* Multipliers: a+, a*, a?, a{2,5}
* Subpatterns: (a|b|\s)

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Florian Stascheck](https://github.com/levu42)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
