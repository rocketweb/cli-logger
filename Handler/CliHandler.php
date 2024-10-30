<?php declare(strict_types=1);
namespace RocketWeb\CliLogger\Handler;

use Monolog\Logger;
use RocketWeb\CliLogger\Formatter\CliFormatter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CliHandler extends \Monolog\Handler\StreamHandler
{
    private static bool $isEnabled = false;
    private ?InputInterface $input = null;
    private ?OutputInterface $output = null;

    public static function setIsEnabled(bool $isEnabled): void
    {
        self::$isEnabled = $isEnabled;
    }

    public function __construct(
        $level = Logger::DEBUG,
        bool $bubble = true,
        ?int $filePermission = null,
        bool $useLocking = false
    ) {
        parent::__construct('php://stdout', $level, $bubble, $filePermission, $useLocking);
    }

    public function setInput(InputInterface $input, OutputInterface $output): void
    {
        $this->input = $input;
        $this->output = $output;
    }

    public function write(array $record): void
    {
        if ($this->input === null ||$this->output === null) {
            // Input or Output not set, skipping ...
            return;
        }

        if (!$this->input->getOption('echoLog') && !self::$isEnabled) {
            // Not enabled, we are not outputting anything!
            return;
        }

        if ($this->output->isQuiet()) {
            // -q is used in the command, we are not outputting anything!
            return;
        }

        if (($record['level'] >= Logger::ERROR && $this->output->isVerbose())
         || ($record['level'] >= Logger::NOTICE && $this->output->isVeryVerbose())
         || $this->output->isDebug()
        ) {
            parent::write($record);
        }
    }

    public function getDefaultFormatter(): CliFormatter
    {
        // Bypassing Magento DI here on purpose. If a change is needed, the method itself can get a plugin
        return new CliFormatter();
    }
}
