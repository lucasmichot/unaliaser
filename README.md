# Unaliaser

A PHP library to track, de-duplicate and un-alias Google Mail and Google Apps emails.

[![Latest Stable Version](https://poser.pugx.org/lucasmichot/unaliaser/v/stable.png)](https://packagist.org/packages/lucasmichot/unaliaser)
[![Total Downloads](https://poser.pugx.org/lucasmichot/unaliaser/downloads.png)](https://packagist.org/packages/lucasmichot/unaliaser)
[![Build Status](https://travis-ci.org/lucasmichot/unaliaser.png)](https://travis-ci.org/lucasmichot/unaliaser)

* [Requiring/Loading](#requiringloading)
* [Methods](#methods)
 * [__construct()](#__construct)
 * [cleanEmail()](#cleanEmail)
 * [domainName()](#domainName)
 * [isGmail()](#isGmail)
 * [isGoogleApps()](#isGoogleApps)
 * [isGoogle()](#isGoogle)
 * [uniqueDomainName()](#uniqueDomainName)
 * [userName()](#userName)
 * [userAlias()](#userAlias)
 * [hasUserAlias()](#hasUserAlias)
 * [userOrigin()](#userOrigin)
 * [userUndottedOrigin()](#userUndottedOrigin)
 * [unique()](#unique)
 * [userIsDotted()](#userIsDotted)
 * [isUnique()](#isUnique)
* [Tests](#tests)
* [License](#license)

## Requiring / Loading

If you're using Composer to manage dependencies, you can include the following
in your `composer.json` file:

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
require_once 'path/to/Unaliaser/src/Unaliaser/Unaliaser.php';
```

## Methods

#### __construct()

Creates a new instance of `Unaliaser`.

```php
$unaliaser = new Unaliaser('foo@bar.com');
```

**If the email is not valid, an `InvalidArgumentExcpetion` will be thrown.**

#### cleanEmail()

Returns a clean email.

```php
$unaliaser = new Unaliaser('  FOO@BAR.COM  ');
echo $unaliaser->cleanEmail();
// 'foo@bar.com'
```

#### domainName()

Returns a the domain name for the email.

```php
$unaliaser = new Unaliaser('foo@bar.com');
echo $unaliaser->domainName();
// 'bar.com'
```

#### isGmail()

Check if an email is managed by Google Mail.

```php
$unaliaser = new Unaliaser('johndoe@gmail.com');
echo $unaliaser->isGmail();
// true

$unaliaser = new Unaliaser('johndoe@yahoo.com');
echo $unaliaser->isGmail();
// false
```

#### isGoogleApps()

Check if an email is managed by Google Apps.

```php
$unaliaser = new Unaliaser('johndoe@gmail.com');
echo $unaliaser->isGoogleApps();
// false

$unaliaser = new Unaliaser('johndoe@yahoo.com');
echo $unaliaser->isGoogleApps();
// false

$unaliaser = new Unaliaser('johndoe@semalead.com');
echo $unaliaser->isGoogleApps();
// true
```

#### isGoogle()

Check if an email is managed by Google (Gmail or Google Apps).

```php
$unaliaser = new Unaliaser('johndoe@gmail.com');
echo $unaliaser->isGoogle();
// true

$unaliaser = new Unaliaser('johndoe@yahoo.com');
echo $unaliaser->isGoogle();
// false

$unaliaser = new Unaliaser('johndoe@semalead.com');
echo $unaliaser->isGoogle();
// true
```

#### uniqueDomainName()

Get the unique domain name for a Gmail address.

```php
$unaliaser = new Unaliaser('johndoe@gmail.com');
echo $unaliaser->uniqueDomainName();
// 'gmail.com'

$unaliaser = new Unaliaser('johndoe@googlemail.com');
echo $unaliaser->uniqueDomainName();
// 'googlemail.com'

$unaliaser = new Unaliaser('johndoe@yahoo.com');
echo $unaliaser->uniqueDomainName();
// 'yahoo.com'
```

#### mxRecords()

Get the MX records for the domain name of an email address.

```php
$unaliaser = new Unaliaser('johndoe@gmail.com');
print_r($unaliaser->mxRecords());
// display an array containing various MX records for the domain of this email address
```

#### userName()

Get the username for an email address.

```php
$unaliaser = new Unaliaser('johndoe@gmail.com');
echo $unaliaser->userName();
// 'johndoe'
```

#### userAlias()

Get the user alias for a Google address, if any.

```php
$unaliaser = new Unaliaser('johndoe+dummyalias@gmail.com');
echo $unaliaser->userAlias();
// 'dummyalias'

$unaliaser = new Unaliaser('johndoe@gmail.com');
echo $unaliaser->userAlias();
// null
```

#### hasUserAlias()

Determines if a Google email address contains an user alias.

```php
$unaliaser = new Unaliaser('johndoe+alias@yahoo.com');
echo $unaliaser->hasUserAlias();
// false

$unaliaser = new Unaliaser('johndoe+alias@gmail.com');
echo $unaliaser->hasUserAlias();
// true
```

#### userOrigin()

Get the original username for a email.

```php
$unaliaser = new Unaliaser('johndoe+alias@yahoo.com');
echo $unaliaser->userOrigin();
// 'johndoe+alias'

$unaliaser = new Unaliaser('johndoe+alias@gmail.com');
echo $unaliaser->userOrigin();
// 'johndoe'
```

#### userUndottedOrigin()

Get the original un-dotted username for a Google address.

```php
$unaliaser = new Unaliaser('john.doe@yahoo.com');
echo $unaliaser->userUndottedOrigin();
// 'john.doe'

$unaliaser = new Unaliaser('john.doe@gmail.com');
echo $unaliaser->userUndottedOrigin();
// 'johndoe'
```


#### userIsDotted()

Determines if a Google address is dotted.

```php
$unaliaser = new Unaliaser('john.doe@yahoo.com');
echo $unaliaser->userIsDotted();
// false

$unaliaser = new Unaliaser('john.doe@gmail.com');
echo $unaliaser->userIsDotted();
// true
```

#### unique()

Get the unique email address.

```php
$unaliaser = new Unaliaser('john.doe+alias@yahoo.com');
echo $unaliaser->unique();
// 'john.doe+alias@yahoo.com'

$unaliaser = new Unaliaser('john.doe+alias@googlemail.com');
echo $unaliaser->unique();
// 'johndoe@gmail.com'
```

#### isUnique()

Determines if the provided email is unique (dotted or aliased).

```php
$unaliaser = new Unaliaser('john.doe+alias@yahoo.com');
echo $unaliaser->isUnique();
// true

$unaliaser = new Unaliaser('john.doe+alias@googlemail.com');
echo $unaliaser->isUnique();
// false

$unaliaser = new Unaliaser('johndoe@googlemail.com');
echo $unaliaser->isUnique();
// false

$unaliaser = new Unaliaser('johndoe@gmail.com');
echo $unaliaser->isUnique();
// true
```

## Tests

From the project directory, tests can be ran using `phpunit`

## License

Released under the MIT License - see `LICENSE.txt` for details.
