# Unaliaser

A PHP library to track, deduplicate and unalias Google Mail and Google Apps emails.

[![Latest Stable Version](https://poser.pugx.org/lucasmichot/unaliaser/v/stable.png)](https://packagist.org/packages/lucasmichot/unaliaser)
[![Total Downloads](https://poser.pugx.org/lucasmichot/unaliaser/downloads.png)](https://packagist.org/packages/lucasmichot/unaliaser)
[![Build Status](https://travis-ci.org/lucasmichot/unaliaser.png)](https://travis-ci.org/lucasmichot/unaliaser)

* [Requiring/Loading](#requiringloading)
* [Methods](#methods)
* [Tests](#tests)
* [License](#license)

## Requiring / Loading

If you're using Composer to manage dependencies, you can include the following
in your composer.json file:

    "require": {
        "unaliaser/unaliaser": "dev-master"
    }

Then, after running `composer update` or `php composer.phar update`, you can
load the class using Composer's autoloading:

```php
require 'vendor/autoload.php';
```

Otherwise, you can simply require the file directly:

```php
require_once 'path/to/Unaliaser/src/Unaliaser/Unaliaser';
```

## Methods

In the list below, any static method other than S::create refers to a method in
`Stringy\StaticStringy`. For all others, they're found in `Stringy\Stringy`.
Furthermore, all methods that return a Stringy object or string do not modify
the original.

#### __construct

Creates a new instance of `Unaliaser`.

```php
$unaliaser = new Unaliaser('foo@bar.com');
```

* If the email, is not valid an `InvalidArgumentExcpetion` will be thrown. *

#### cleanEmail

Returns a clean email.

```php
$unaliaser = new Unaliaser(' FOO@BAR.COM ');
echo $unaliaser->cleanEmail();
// 'foo.bar.com'
```

## Todo

* Create documentation in README.md
* Add to Packagist

## Tests

From the project directory, tests can be ran using `phpunit`

## License

Released under the MIT License - see `LICENSE.txt` for details.
