<?php declare(strict_types=1);
namespace RocketWeb\CliLogger\Formatter;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class CliFormatter implements \Monolog\Formatter\FormatterInterface
{
    private const LEVEL_COLOR = [
        'debug' => 'green',
        'info' => 'green',
        'notice' => 'cyan',
        'warning' => 'cyan',
        'error' => 'magenta',
        'critical' => 'red',
        'alert' => 'red',
        'emergency' => 'red'
    ];

    public function format(array $record): string
    {
        $newline = isset($record['context']['newline']) && !$record['context']['newline'] ? '' : "\n";
        $level = strtolower($record['level_name']);
        $message = sprintf('%s.%s: %s', $record['channel'], $record['level_name'], $record['message']);
        if (is_array($record['context']) && count($record['context']) > 0) {
            $message .= json_encode($record['context']);
        }
        $message = $this->cleanUp($message, $level);

        return $message . $newline;
    }

    public function formatBatch(array $records): string
    {
        $message = '';
        foreach ($records as $record) {
            $message .= $this->format($record);
        }

        return $message;
    }

    private function cleanUp(string $message, string $level): string
    {
        foreach (array_keys(self::LEVEL_COLOR) as $tag) {
            $staringTag = sprintf('<%s>', $tag);
            $endingTag = sprintf('</%s>', $tag);
            $message = str_replace($staringTag, '', $message);
            $message = str_replace($endingTag, '', $message);
        }

        $color = self::LEVEL_COLOR[$level] ?? 'default';

        return (new OutputFormatterStyle($color))->apply(
            \Symfony\Component\Console\Formatter\OutputFormatter::escape($message)
        );
    }
}
