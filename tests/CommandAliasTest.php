<?php
namespace Cmizzi\CommandAlias\Tests;

use Cmizzi\CommandAlias\Console\GenericCommandAlias;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel as Artisan;

class CommandAliasTest extends TestCase {
	/**
	 * @override
	 * @return void
	 */
	protected function setUp() {
		parent::setUp();

		$this->artisan = $this->app[Artisan::class];
		$this->artisan->registerCommand($this->getInspireCommandClass());
	}

	/**
	 * check_alias_definition
	 *
	 * @return void
	 * @test
	 */
	public function check_alias_definition() {
		$command = new GenericCommandAlias('i', ['inspire', ['--no-output' => true]]);

		$this->assertSame('i', $command->getName());
		$this->assertSame('Alias for inspire command', $command->getDescription());
		$this->assertSame('inspire', $command->getAliasName());
		$this->assertSame(['--no-output' => true], $command->getAliasArguments());
	}

	/**
	 * call_alias_without_parameter
	 *
	 * @return void
	 * @test
	 */
	public function call_alias_without_parameter() {
		$this->artisan->registerCommand(new GenericCommandAlias('i', 'inspire'));
		$this->artisan->call('i');

		$this->assertSame('some inspiration', trim($this->artisan->output()));
	}

	/**
	 * call_alias_with_parameter
	 *
	 * @return void
	 * @test
	 */
	public function call_alias_with_parameter() {
		$this->artisan->registerCommand(new GenericCommandAlias('i', ['inspire', ['--no-output' => true]]));
		$this->artisan->call('i');

		$this->assertEmpty($this->artisan->output());
	}

	/**
	 * Returns inspire command anynymous class
	 */
	protected function getInspireCommandClass() {
		return new class extends Command {
			protected $signature = 'inspire {--no-output}';

			/**
			 * Returns inspiration
			 *
			 * @return void
			 */
			public function handle() {
				if (!$this->option('no-output')) {
					$this->info('some inspiration');
				}
			}
		};
	}
}
