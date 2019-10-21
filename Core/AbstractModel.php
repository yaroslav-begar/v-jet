<?php

namespace Core;

use PDO;
use App\Config;

abstract class AbstractModel
{
    protected static $table;

    protected $data = [];

    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function __get($key)
    {
        return $this->data[$key];
    }

    public function insert()
    {
        $sql = '
            INSERT INTO ' . static::$table . ' (' . implode(', ', array_keys($this->data)) . ') 
			VALUES (
        ';
        foreach($this->data as $data) {
            $sql .= "'{$data}',";
        }
        $sql = substr($sql, 0, -1);
        $sql .= ')';

        $db = static::getDB();
        $db->exec($sql);

        $this->id = $db->lastInsertId();
    }

    protected static function getDB()
    {
        static $db = null;

        if ($db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
            $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }
}
