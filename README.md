<h1 align="center">Germania KG · ClearCache</h1>

<p align="center"><b>Symfony Console Command for clearing caches in our web apps.</b></p>
[![Tests](https://github.com/GermaniaKG/ClearCacheCommand/actions/workflows/tests.yml/badge.svg)](https://github.com/GermaniaKG/ClearCacheCommand/actions/workflows/tests.yml)

## Installation

```bash
$ composer require germania-kg/clearcache-command:^1.0
```

## Requirements

This package requires  the ***Symfony Console*** component and the ***Psr\Cache*** interfaces.

## Usage

The ClearCacheCommand clears app cache directories and PSR Cache Item Pools:

```php
use Germania\ClearCache\ClearCacheCommand;

$directories = array();
$psr_cacheitempools = array();

$cmd = new ClearCacheCommand($directories, $psr_cacheitempools);
```

## CLI usage

The command name is `cache:clear`, and it accepts a `--dry-run` option:

```bash
$ bin/console cache:clear
$ bin/console cache:clear --dry-run
```



## Development

```bash
$ git clone git@github.com:GermaniaKG/ClearCacheCommand.git
# or
$ git clone https://github.com/GermaniaKG/ClearCacheCommand.git
```

## Unit tests and development

1. Copy `phpunit.xml.dist` to `phpunit.xml` 
2. Run [PhpUnit](https://phpunit.de/) like this:

```bash
$ composer test
# or
$ vendor/bin/phpunit
```

And there's more in the `scripts` section of **composer.json**.

