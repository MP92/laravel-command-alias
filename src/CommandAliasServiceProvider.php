<?php
namespace Cmizzi\CommandAlias;

use Cmizzi\CommandAlias\Console\GenericCommandAlias;
use Illuminate\Support\ServiceProvider;

class CommandAliasServiceProvider extends ServiceProvider {
	/**
	 * boot
	 *
	 * @return void
	 */
	public function boot() {
		$this->publishes([
			__DIR__ . '/' . '../config/command-alias.php' => config_path('command-alias.php')
		]);
	}

	/**
	 * register
	 *
	 * @return void
	 */
	public function register() {
		$this->mergeConfigFrom(__DIR__ . '/' . '../config/command-alias.php', 'command-alias');

		foreach (config('command-alias.commands', []) as $name => $command) {
			$bind = "command.command-alias:$name";

			$this->app->bind($bind, new GenericCommandAlias($name, $command));
			$this->commands([$bind]);
		}
	}
}
