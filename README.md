# Laravel Artisan Command Alias

This package allows you to create some aliases for Artisan purpose. It's like `bash`, but for Artisan.

## Installation

You can install the package via composer:

```bash
composer require cmizzi/laravel-command-alias
```

## Usage

First, you need to publish the configuration file which storing all your availabled aliases with

```bash
php artisan vendor:publish --provider="Cmizzi\CommandAlias\CommandAliasServiceProvider"
```

Now, edit `config/command-alias.php` and creates yours like

```php
<?php

/*
 * Configuration file for CommandAlias package
 */
return [
	/*
	 * Register all your alias in the next array. You can use string association
	 * if there's no argument.
	 *
	 * https://laravel.com/docs/5.6/artisan#programmatically-executing-commands
	 */
	'commands' => [
		'i' => 'inspire',
		'lsr' => ['route:list', ['--reverse' => true]],
	]
];
```

Personnally, I've created a `bash` alias for Artisan like `alias a="php artisan"` and I execute command like `a i` or `a lsr` as `git` (`alias g="git"` ; `g lga`).

## Credits

- [Cyril MIZZI](https://github.com/cmizzi)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
