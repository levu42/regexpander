# PHP library that expands a regular expression into a random string that matches this pattern

[![Latest Version on Packagist](https://img.shields.io/packagist/v/levu42/regexpander.svg?style=flat-square)](https://packagist.org/packages/levu42/regexpander)
[![Tests](https://github.com/levu42/regexpander/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/levu42/regexpander/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/levu42/regexpander.svg?style=flat-square)](https://packagist.org/packages/levu42/regexpander)

This is where your description should go. Try and limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/regexpander.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/regexpander)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require levu42/regexpander
```

## Usage

```php
$skeleton = new Levu42\RegExpander();
echo $skeleton->echoPhrase('Hello, Levu42!');
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

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Florian Stascheck](https://github.com/levu42)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
