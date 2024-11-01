# cli-logger
Module log wrapper that supports CLI output
# RocketWeb_CliLogger module
This module is a simple helper module that allows for outputting all log data into CLI output in parallel to writing into the logs. The idea is simple - there are times when you need to debug a command to see what gets written into a log. This module solves for that. With a simple `--echoLog=1` and adding verbosity level `-v/-vv/-vvv` to the command you see whats being written into logs!

## Installation
Thru composer as dev package:
```
composer require --dev rocket-web/module-cli-logger
```
Despite this being a dev package, it is a Magento 2 module, so you must enable it:
```
bin/magento module:enable RocketWeb_CliLogger
```

## Usage
There are two ways to enable the logs being echoed to CLI output:
1. Using a hidden command parameter `--echoLog=1`
2. Using a static method in code:
```
\RocketWeb\CliLogger\Handler\CliHandler::setIsEnabled(true);
```
In both cases, the verbosity level needs to be enabled `-v/-vv/-vvv`. This allows for having the method called in code but
not seeing log data until needed.

The mapping between verbosity levels and log levels is:
- `-v` will output anything `Logger::ERROR` or higher (`Logger::CRITICAL`, `Logger::ALERT`, `Logger::EMERGENCY`)
- `-vv` will output `-v`and `Logger::NOTICE` and `Logger::WARNING`
- `-vvv` will output `-vv` and `Logger::INFO` and `Logger::DEBUG`

The output is color coded:
```
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
```

If the output gives too much detail (for example main.DEBUG can throw too much out), just pipe to ` | grep -v` to filter that out! Example:
``` 
bin/magento custom:command --echoLog=1 -vvv | grep -v "main.DEBUG"
```
