# Getting started

- [Installation](#installation)
- [Basic usage](#basic-usage)
- [Extensions](#extensions)
- [Configuring allowed attributes](#configuring-allowed-attributes)
- [Configuring blocked attributes](#configuring-blocked-attributes)
- [Configuring allowed classes](#configuring-allowed-classes)
- [Configuring blocked classes](#configuring-blocked-classes)
- [Configuring childless tags](#configuring-childless-tags)
- [Converting tags](#converting-tags)

## Installation

html-sanitizer requires PHP 7.1+.

You can install the library using the following command:

```
composer require snebes/html-sanitizer
```

## Basic usage

The main entry point to the sanitizer is the `SN\HtmlSanitizer\Sanitizer` class. It requires
an array of configuration:

```php
$sanitizer = SN\HtmlSanitizer\Sanitizer::create(['extensions' => ['html5']]);
$safeHtml = $sanitizer->sanitize($untrustedHtml);
```

The sanitizer works using *extensions*. Extensions are a set of features that you can easily
enable to allow specific tags in the content (read the next part to learn more about them). 

> Note that the sanitizer is working using a strict whitelist of allowed tags: in the previous example,
> the sanitizer would allow **only** the basic HTML5 tags (`strong`, `a`, `div`, etc., ).

## Extensions

Extensions are a way to quickly add sets of tags to the whitelist of allowed tags.
There is 1 core extension that you can enable by adding them in your configuration:

```php
$sanitizer = SN\HtmlSanitizer\Sanitizer::create([
    'extensions' => ['html5'],
]);
$safeHtml = $sanitizer->sanitize($untrustedHtml);
```

Here is the list of tags each extension allow:

- **html5** allows the insertion of basic HTML elements:
  `a`, `abbr`, `address`, `applet`, `area`, `article`, `aside`, `audio`, `b`, `base`, `bdi`, `bdo`, `blockquote`,
  `body`, `br`, `button`, `canvas`, `caption`, `cite`, `code`, `col`, `colgroup`, `content`, `data`, `datalist`, `dd`,
  `del`, `details`, `dfn`, `dialog`, `dir`, `div`, `dl`, `dt`, `element`, `em`, `embed`, `fieldset`, `figcaption`, 
  `figure`, `footer`, `form`, `h1`, `h2`, `h3`, `h4`, `h5`, `h6`, `head`, `header`, `hgroup`, `hr`, `html`, `i`, 
  `iframe`, `img`, `input`, `ins`, `kbd`, `label`, `legend`, `li`, `link`, `main`, `map`, `mark`, `menu`, `menuitem`, 
  `meta`, `meter`, `nav`, `noembed`, `noscript`, `object`, `ol`, `optgroup`, `option`, `output`, `p`, `param`, 
  `picture`, `pre`, `progress`, `q`, `rb`, `rp`, `rt`, `rtc`, `ruby`, `s`, `samp`, `script`, `section`, `select`, 
  `shadow`, `slot`, `small`, `source`, `span`, `strong`, `style`, `sub`, `summary`, `sup`, `table`, `tbody`, `td`, 
  `template`, `textarea`, `tfoot`, `th`, `thead`, `time`, `title`, `tr`, `track`, `tt`, `u`, `ul`, `var`, `video`, `wbr`

> Note: sensible attributes are allowed by default for each tag (for instance, the `src` attribute is
> allowed by default on images). You can also
> [override these allowed attributes manually](#configuring-allowed-attributes) if you need to.

## Configuring allowed attributes

The core extensions define sensible default allowed attributes for each tag, which mean you usually won't need
to change them. However, if you want to customize which attributes are allowed on specific tags, you can use
a tag-specific configuration for them. 

For instance, to allow only the configured attributes on the `div` and `img` tags, you can use the following 
configuration:

```php
$sanitizer = HtmlSanitizer\Sanitizer::create([
    'extensions' => ['html5'],
    'tags' => [
        'div' => [
            'allowed_attributes' => ['class'],
        ],
        'img' => [
            'allowed_attributes' => ['src', 'alt', 'title', 'class'],
        ],
    ],
]);
```

## Configuring blocked attributes

The core extensions by default allow any attribute to be used on a tag. However, if you want to customize which
attributes are blocked on specific tags, you can use a tag-specific configuration for them. 

For instance, to only disallow the `class` attribute on the `div` tags, you can use the following configuration:

```php
$sanitizer = HtmlSanitizer\Sanitizer::create([
    'extensions' => ['html5'],
    'tags' => [
        'div' => [
            'blocked_attributes' => ['class'],
        ],
    ],
]);
```

## Configuring allowed classes

The core extensions by default allow any class to be used on a tag, which mean you usually won't need
to change them. However, if you want to customize which classes are allowed on specific tags, you can use
a tag-specific configuration for them. 

For instance, to only allow the `d-flex` class on the `div` tags, you can use the following configuration:

```php
$sanitizer = HtmlSanitizer\Sanitizer::create([
    'extensions' => ['html5'],
    'tags' => [
        'div' => [
            'allowed_classes' => ['d-flex'],
        ],
    ],
]);
```

## Configuring blocked classes

The core extensions by default allow any class to be used on a tag. However, if you want to customize which
classes are blocked on specific tags, you can use a tag-specific configuration for them. 

For instance, to only disallow the `float` class on the `div` tags, you can use the following configuration:

```php
$sanitizer = HtmlSanitizer\Sanitizer::create([
    'extensions' => ['html5'],
    'tags' => [
        'div' => [
            'blocked_classes' => ['float'],
        ],
    ],
]);
```

## Configuring childless tags

The core extensions by default sets the `area`, `base`, `br`, `col`, `img`, `input`, `hr`, `link`, `meta`,
`param`, `track`,  `wbr` tags as childless tags, which mean you usually won't need to change them.
However, if you want to customize which tags are childless, you can use a tag-specific configuration for them. 

For instance, you can use the following configuration:

```php
$sanitizer = HtmlSanitizer\Sanitizer::create([
    'extensions' => ['html5'],
    'tags' => [
        'hr' => [
            'childless' => true,
        ],
    ],
]);
```

## Converting tags

There may be an instance where you want to prevent the use of a deprecated tag that may have been
superseded by another tag.

For instance, to convert `b` tags to `strong` tags, you can use the following configuration:

```php
$sanitizer = HtmlSanitizer\Sanitizer::create([
    'extensions' => ['html5'],
    'tags' => [
        'strong' => [
            'convert_elements' => ['b'],
        ],
    ],
]);
```
