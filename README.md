# html-sanitizer

[![PHP Version](https://img.shields.io/packagist/php-v/snebes/html-sanitizer.svg?maxAge=3600)](https://packagist.org/packages/snebes/html-sanitizer)
[![Latest Version](https://img.shields.io/packagist/v/snebes/html-sanitizer.svg?maxAge=3600)](https://packagist.org/packages/snebes/html-sanitizer)
[![Build Status](https://img.shields.io/scrutinizer/build/g/snebes/html-sanitizer.svg?maxAge=3600)](https://scrutinizer-ci.com/g/snebes/html-sanitizer)
[![Code Quality](https://img.shields.io/scrutinizer/g/snebes/html-sanitizer.svg?maxAge=3600)](https://scrutinizer-ci.com/g/snebes/html-sanitizer)
[![Test Coverage](https://img.shields.io/scrutinizer/coverage/g/snebes/html-sanitizer.svg?maxAge=3600)](https://scrutinizer-ci.com/g/snebes/html-sanitizer)

html-sanitizer is a library aiming at handling, cleaning and sanitizing HTML sent by external users
(who you cannot trust), allowing you to store it and display it safely. It has sensible defaults
to provide a great developer experience while still being entirely configurable.

Internally, the sanitizer has a deep understanding of HTML: it parses the input and create a tree of
DOMNode objects, which it uses to keep only the safe elements from the content. By using this
technique, it is safe (it works with a strict whitelist), fast and easily extensible.

It also provides useful features such as the possibility to transform images or iframes URLs to HTTPS.

## Security Issues

If you discover a security vulnerability within the sanitizer, please follow
[our disclosure procedure](https://github.com/snebes/html-sanitizer/blob/master/docs/A-security-disclosure-procedure.md).

## Backward Compatibility promise

This library follows the same Backward Compatibility promise as the Symfony framework:
[https://symfony.com/doc/current/contributing/code/bc.html](https://symfony.com/doc/current/contributing/code/bc.html)

> *Note*: many classes in this library are either marked `@final` or `@internal`.
> `@internal` classes are excluded from any Backward Compatiblity promise (you should not use them in your code)
> whereas `@final` classes can be used but should not be extended (use composition instead).

## Thanks

Many thanks to:
- [The Open Web Application Security Project](https://www.owasp.org/index.php/OWASP_Java_HTML_Sanitizer_Project) 
  from which many of the tests of this library are extracted (more specifically from
  [OWASP/java-html-sanitizer](https://github.com/OWASP/java-html-sanitizer)) ;
- [Masterminds/html5-php](https://github.com/Masterminds/html5-php) which is a great HTML5 parser, used by default
  in this library ;
- [tgalopin/html-sanitizer](https://github.com/tgalopin/html-sanitizer): from which this library is a hard-fork from.
