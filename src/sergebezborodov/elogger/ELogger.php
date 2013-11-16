<?php


namespace sergebezborodov\elogger;

use yii\base\Component,
    yii\base\InvalidConfigException;


/**
 * Logger class
 *
 * @property array $handlers
 * @property array $levels
 */
class ELogger extends Component
{
    const TRACE		= 'trace';
    const LOG		= 'log';

    const SUCCESS	= 'success';
    const ERROR		= 'error';

    const FATAL		= 'fatal';

    /**
     * @var array logger handlers configuration
     */
    private $_handlersConfig = array(
        'file',
        'console',
    );

    /**
     * @var BaseHandler[]
     */
    private $_handlers;

    /**
     * @var array known levels to log
     */
    protected $_levels = array(
        self::TRACE,
        self::LOG,
        self::SUCCESS,
        self::ERROR,
        self::FATAL,
    );


    /**
     * Init class, creates handlers
     */
    public function init()
    {
        parent::init();

        if (empty($this->_handlersConfig)) {
            throw new InvalidConfigException('Empty handlers config');
        }
    }

    /**
     * Sets handlers configs
     *
     * @param array $handlers
     */
    public function setHandlers($handlers)
    {
        $this->_handlersConfig = $handlers;
    }

    /**
     * Return array of handlers instances
     *
     * @return BaseHandler[]
     */
    protected function getHandlers()
    {
        if ($this->_handlers == null) {
            $this->_handlers = array();
            foreach ($this->_handlersConfig as $config) {
                $this->_handlers[] = $this->createHandler($config);
            }
        }
        return $this->_handlers;
    }

    /**
     * Create handler instance from config
     *
     * @param array|string $config
     * @return BaseHandler
     */
    protected function createHandler($config)
    {
        if (is_string($config)) {
            if (!isset(BaseHandler::$bultInHandlers[$config])) {
                throw new InvalidConfigException("Unknown type '{$config}' for handler config");
            }
            $config = array('class' => BaseHandler::$bultInHandlers[$config]);
        }
        $handler = \Yii::createObject($config, $this);
        $handler->init();
        return $handler;
    }

    /**
     * Add addition log levels
     *
     * @param array $levels
     */
    public function setLevels($levels)
    {
        if (is_string($levels)) {
            $levels = preg_split('/[\s,]+/', $levels, -1, PREG_SPLIT_NO_EMPTY);
        }

        $this->_levels = array_unique(array_merge($this->_levels, $levels));
    }

    /**
     * @return array all levels for logger
     */
    public function getLevels()
    {
        return $this->_levels;
    }


    /**
     * Write log message
     *
     * @abstract
     * @param mixed $message message to log
     * @param string|null $target target name (file, category, etc)
     * @param string $level log level
     * @param string $from string from __METHOD__ constant
     * @param array|null $data addition data to handler
     *
     * @return int success processed handlers
     */
    public function write($message, $target = null, $level = self::TRACE, $from = null, $data = null)
    {
        $success = 0;
        foreach ($this->getHandlers() as $hander) {
            if ($hander->write($message, $target, $level, $from, $data)) {
                $success++;
            }
        }
        return $success;
    }

    /**
     * Logs a message.
     *
     * @param string $message message to be logged
     * @param string $level level of the message (e.g. 'Trace', 'Warning', 'Error'). It is case-insensitive.
     * @param string $category category of the message (e.g. 'system.web'). It is case-insensitive.
     */
    public function log($message, $level = 'trace', $category = 'application')
    {
        $this->write($message, $category, $level);
    }
}