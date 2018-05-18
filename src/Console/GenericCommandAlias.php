<?php
namespace Cmizzi\CommandAlias\Console;

use Illuminate\Console\Command;

class GenericCommandAlias extends Command {
	/** @var string $alias */
	protected $alias;

	/**
	 * __construct
	 *
	 * @param  string $name
	 * @param  string|array $command
	 * @return void
	 */
	public function __construct(string $name, $command) {
		$this->alias = $command;
		$this->name = $name;

		parent::__construct();
		$this->setDescription(sprintf('Alias for %s command', $this->getAliasName()));
	}

	/**
	 * handle
	 *
	 * @return void
	 */
	public function handle() {
		$this->call($this->getAliasName(), $this->getAliasArguments());
	}

	/**
	 * Returns alias name
	 *
	 * @return string
	 */
	public function getAliasName(): string {
		if (is_array($this->alias)) {
			return head($this->alias);
		}

		return $this->alias;
	}

	/**
	 * Returns alias arguments
	 *
	 * @return array
	 */
	public function getAliasArguments(): array {
		if (is_array($this->alias) and is_array(last($this->alias))) {
			return last($this->alias);
		}

		return [];
	}
}
