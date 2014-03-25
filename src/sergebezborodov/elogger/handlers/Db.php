<?php


namespace sergebezborodov\elogger\handlers;

use Yii,
    sergebezborodov\elogger\BaseHandler,
    sergebezborodov\elogger\ELogger,
    yii\db\Connection,
    yii\base\InvalidConfigException,
    yii\db\Command;


/**
 * DB handler stores log messages into databse
 *
 * @package sergebezborodov\elogger\handlers
 */
class Db extends BaseHandler
{
    /**
     * @var Connection|string the DB Connection or the application component ID of DB connection
     * After the DbTarget object is created, if you want to change this property, you should only assign it
     * with a DB connection object.
     */
    public $db = 'db';

    /**
     * Table name
     *
     * ~~~
     * CREATE TABLE tbl_log (
     *	   id       BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
     *	   level    VARCHAR(20),
     *	   target   VARCHAR(255),
     *     source   VARCHAR(255),
     *	   message  TEXT,
     *     date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
     *     INDEX idx_log_level (level),
     *     INDEX idx_log_category (target)
     * )
     * ~~~
     * @var string
     */
    public $table = 'tbl_log';

    /**
     * @var Command
     */
    protected $command;


    /**
     * Init handler
     */
    public function init()
    {
        parent::init();

        if (is_string($this->db)) {
            $this->db = Yii::$app->get($this->db);
        }
        if (!$this->db instanceof Connection) {
            throw new InvalidConfigException("Db::db must be either a DB connection instance or the application component ID of a DB connection.");
        }

        $tableName = $this->db->quoteTableName($this->table);
        $sql = "INSERT INTO $tableName ([[level]], [[target]], [[message]], [[source]])
				VALUES (:level, :target, :message, :source)";
        $this->command = $this->db->createCommand($sql);
    }


    /**
     * Write log message
     *
     * @param mixed       $message message to log
     * @param string|null $target target name (file, category, etc)
     * @param string      $level log level
     * @param string      $from string from __METHOD__ constant
     * @param array|null  $data addition data to handler
     * @return bool
     */
    public function write($message, $target = null, $level = ELogger::TRACE, $from = null, $data = null)
    {
        $this->command->bindValues(array(
            ':message' => $message,
            ':level'   => $level,
            ':target'  => $target,
            ':source'  => $from,
        ))->execute();
    }
}