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
		$command = new GenericCommandAlias('i', [
		    'name' => 'inspire',
            'params' => ['--no-output' => true]
        ]);

		$this->assertSame('i', $command->getName());
		$this->assertSame('Alias for inspire command', $command->getDescription());
		$this->assertSame('inspire', $command->getAliasName());
		$this->assertSame(['--no-output' => true], $command->getAliasParams());
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
		$this->artisan->registerCommand(new GenericCommandAlias('i', [
		    'name' => 'inspire',
            'params' => ['--no-output' => true]
        ]));
		$this->artisan->call('i');

		$this->assertEmpty($this->artisan->output());


		$msg = 'custom inspiration';

        $this->artisan->registerCommand(new GenericCommandAlias('i', [
            'name' => 'inspire',
            'params' => ['msg' => $msg]
        ]));
        $this->artisan->call('i');

        $this->assertSame($msg, trim($this->artisan->output()));
	}

    /**
     * check_alias_with_cmd_parameter
     *
     * @return void
     * @test
     */
    public function check_alias_with_cmd_parameter() {
        $aliasCommand = new GenericCommandAlias('i', [
            'name' => 'inspire',
            'signature' => 'i {msg}'
        ]);
        $this->assertTrue($aliasCommand->getDefinition()->hasArgument('msg'));
        $this->assertNull($aliasCommand->getDefinition()->getArgument('msg')->getDefault());


        $msg = 'custom_alias_inspiration';

        $aliasCommand = new GenericCommandAlias('i', [
            'name' => 'inspire',
            'signature' => "i {msg=$msg}"
        ]);
        $this->assertTrue($aliasCommand->getDefinition()->hasArgument('msg'));
        $this->assertSame($msg, trim($aliasCommand->getDefinition()->getArgument('msg')->getDefault()));
    }

    /**
     * call_alias_with_cmd_parameter
     *
     * @return void
     * @test
     */
    public function call_alias_with_cmd_parameter() {
        $msg = 'test';

        $this->artisan->registerCommand(new GenericCommandAlias('i', [
            'name' => 'inspire',
            'signature' => "i {msg=$msg}"
        ]));
        $this->artisan->call('i');

        $this->assertSame($msg, trim($this->artisan->output()));


        $this->artisan->registerCommand(new GenericCommandAlias('i', [
            'name' => 'inspire',
            'signature' => 'i {msg}'
        ]));
        $this->artisan->call('i', ['msg' => $msg]);

        $this->assertSame($msg, trim($this->artisan->output()));

        $this->expectException('\Symfony\Component\Console\Exception\RuntimeException');

       $this->artisan->call('i');
    }

    /**
     * call_alias_with_cmd_parameter
     *
     * @return void
     * @test
     */
    public function call_alias_with_cmd_and_alias_parameters() {
        $this->artisan->registerCommand(new GenericCommandAlias('i', [
            'name' => 'inspire',
            'params' => ['--no-output' => true],
            'signature' => "i {msg}"
        ]));
        $this->artisan->call('i', ['msg' => 'test']);

        $this->assertEmpty($this->artisan->output());
    }

	/**
	 * Returns inspire command anynymous class
	 */
	protected function getInspireCommandClass() {
		return new class extends Command {
			protected $signature = 'inspire {msg=some inspiration} {--no-output}';

			/**
			 * Returns inspiration
			 *
			 * @return void
			 */
			public function handle() {
				if (!$this->option('no-output')) {
					$this->info($this->argument('msg'));
				}
			}
		};
	}
}
