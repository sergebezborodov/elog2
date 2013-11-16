<?php


namespace sergebezborodov\elogger;

use yii\base\Component;


/**
 * Base formatter for logger
 *
 * @property BaseHandler $handler
 */
abstract class BaseFormater extends Component
{
    public static $bultInFormater = array(
        'default' => 'sergebezborodov\elogger\formaters\Standart',
        'console' => 'sergebezborodov\elogger\formaters\ConsoleColor',
    );

    /**
     * @var BaseHandler
     */
    private $_handler;

    /**
     * Creates instance
     *
     * @param BaseHandler $handler
     * @param array $config
     */
    public function __construct($handler, $config = array())
    {
        $this->_handler = $handler;
        \Yii::configure($this, $config);
    }

    /**
     * Init formater
     */
    public function init()
    {}

    /**
     * @return BaseHandler current handler
     */
    protected function getHandler()
    {
        return $this->_handler;
    }

    /**
     * Format log message
     *
     * @abstract
     * @param mixed $message message to log
     * @param string|null $target target name (file, category, etc)
     * @param string $level log level
     * @param string $from string from __METHOD__ constant
     * @param array|null $data addition data to handler
     * @return string
     */
    abstract public function format($message, $target = null, $level = ELogger::TRACE, $from = null, $data = null);
}
