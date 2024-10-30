<?php declare(strict_types=1);
namespace RocketWeb\CliLogger\Plugin;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CommandWrapper
{
    public function __construct(
        private readonly \RocketWeb\CliLogger\Handler\CliHandler $cliHandler
    ) {}

    public function beforeRun(Command $subject, InputInterface $input, OutputInterface $output): ?array
    {
        // Adding a custom command option to enable the logging from command if needed
        $subject->getApplication()->getDefinition()->addOption(
            new InputOption(
                'echoLog',
                null,
                InputOption::VALUE_OPTIONAL,
                'Enabling Log data to be echoed into CLI output'
            )
        );
        // Setting the Input & Output to the handler for validation & verbosity
        $this->cliHandler->setInput($input, $output);
        return null;
    }
}
