<?php
namespace Cmizzi\CommandAlias\Console;

use Illuminate\Console\Command;
use Illuminate\Console\Parser;

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

        if (is_array($command) && isset($command['signature'])) {
            [$name, $arguments, $options] = Parser::parse($command['signature']);

            $this->getDefinition()->addArguments($arguments);
            $this->getDefinition()->addOptions($options);
        }
	}

	/**
	 * handle
	 *
	 * @return void
	 */
	public function handle() {
        $arguments = array_merge($this->getAliasParams(), $this->arguments(), $this->getCmdOptions());
        $this->call($this->getAliasName(), $arguments);
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
    public function getAliasParams(): array {
        if (is_array($this->alias) && isset($this->alias['params']) && is_array($this->alias['params'])) {
            return $this->alias['params'];
        }

        return [];
    }

    private function getCmdOptions(): array {
        $ignore = ['help', 'quiet', 'verbose', 'version', 'ansi', 'no-ansi', 'no-interaction', 'env'];

        $options = array_diff_key(
            $this->options(),
            array_flip($ignore)
        );

        return array_combine(
            array_map(
                function($key) {
                    return '--' . $key;
                },
                array_keys($options)
            ),
            array_values($options)
        );
    }
}
