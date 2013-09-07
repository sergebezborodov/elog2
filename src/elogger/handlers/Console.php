<?php


namespace elogger\handlers;


use elogger\BaseHandler,
    elogger\ELogger;

/**
 * Output log messages with echo
 */
class Console extends BaseHandler
{
    /**
     * @var string|array config for default message formater
     */
    protected $_formaterConfig = 'console';

    /**
     * @var string constant name for checking current is console application
     */
    public $constant = 'CONSOLE_APP';

    /**
     * Check is current application is console
     *
     * @return bool
     */
    protected function getIsConsoleApp()
    {
        if (defined($this->constant)) {
            return true;
        }

        if (\Yii::$app instanceof \yii\console\Application) {
            return true;
        }

        return false;
    }



    /**
     * Write log message
     *
     * @param mixed       $message message to log
     * @param string|null $target  target name (file, category, etc)
     * @param string      $level   log level
     * @param string      $from    string from __METHOD__ constant
     * @param array|null  $data    addition data to handler
     *
     * @return bool
     */
    public function write($message, $target = null, $level = ELogger::TRACE, $from = null, $data = null)
    {
        if (!$this->getIsConsoleApp() || !$this->getIsLoggedLevel($level)) {
            return false;
        }

        echo $this->getFormater()->format($message, $target, $level, $from, $data) . "\r\n";
        return true;
    }
}