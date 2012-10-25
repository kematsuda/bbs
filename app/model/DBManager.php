<?php

class DBManager
{
    private $user = null;
    public $DbName = null;

    public function __construct($DbName)
    {
        $file = fopen(dirname(__FILE__) . '/../../conf/db_usser.conf');
        $this->user = fgets($file);
        $this->DbName = $DbName;
    }

    public static function q($sql, $binds=array())
    {
        $result = array();
        $con = new PDO('mysql:dbname=' . $this->DbName, $this->user);
        $stmt = $con->prepare($sql);
        $stmt->execute($binds);
        return $stmt->fetchAll();
    }

    public static function save($sql, $binds)
    {
        $con = new PDO('mysql:dbname=' . $this->DbName, $this->user);
        $stmt = $con->prepare($sql);
        $stmt->execute($binds);
    }
}