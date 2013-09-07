Logger for Yii 2
===============

Logger component for Yii Framework 2.
Key features:
 - extended logging to files, each file for different log types
 - tunable logging levels
 - ouput all logs to console for console application with colors
 - easy to extend


Install
-------

Fast install:

```php
'components' => array(
    'elog' => array(
        'class' => 'elogger\Elogger',
    )
)

```

All config params:

```php
'components' => array(
    'elog' => array(
        'class' => 'elogger\Elogger',

        // addition log levels
        'levels' => array('warning', 'fail'), // also can be 'levels' => 'warning, fail'

        'handlers' => array(
            // file log
            array(
                'class' => 'elogger\handlers\File',
                // log message formater
                'formatter' => array(
                    'class' => 'elogger\formaters\Standart',
                    // date format
                    'dateFormat' => 'Y-m-d H:i:s',
                    // log message format
                    'messageFormat' => '{date} [{level}]{level-spaces} {from} {message}',
                ),

                // by default file log handler accept all messages from all levels
                // you can specify levels for this handler by array or coma separated string
                // also can set uniq levels that main component doesn't has
                // 'level' => array('trace', 'fullshit'),
                'levels' => '*',

                // default log file
                'defaultFile' => 'application',
                // log file extension (without dot)
                'extension' => 'log',
                // log file directory, by default runtime path
                'path' => 'application.runtime',
                // max log file size, if over file will be rotated
                'maxFileSize' => 1024,
                // max log files count
                'maxFiles' => 10,
                // new directory mode permission
                'directoryMode' => 0777,
                // new log file permission
                'fileMode' => 0640,
            ),

            // color console handler
            array(
                'class' => 'elogger\formaters\ConsoleColor',
                // log message formatter
                'formatter' => array(
                    'class' => 'elogger\formaters\ConsoleColor',
                    // date format
                    'dateFormat' => 'Y-m-d H:i:s',
                    // log message format
                    'messageFormat' => '{date} [{level}]{level-spaces} {from} {message}',
                ),
                // by default file log handler accept all messages from all levels
                // you can specify levels for this handler by array or coma separated string
                // also can set uniq levels that main component doesn't has
                // 'level' => array('trace', 'fullshit'),
                'levels' => '*',
            ),
        ),
    ),
),
```