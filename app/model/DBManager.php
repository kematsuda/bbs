<?php

class DBManager
{
    private static $user = null;
    public static $DbName = null;

    public static function setupDB($DbName)
    {
        $file = fopen(dirname(__FILE__) . '/../../conf/db_user.conf', "r");
        self::$user = trim(fgets($file));
        self::$DbName = 'bbs';
    }

    public static function q($sql, $DbName = 'bbs', $binds=array())
    {
        self::setupDB($DbName);
        $result = array();
        $con = new PDO('mysql:dbname=' . self::$DbName, self::$user);
        $stmt = $con->prepare($sql);
        $stmt->execute($binds);
        return $stmt->fetchAll();
    }

    public static function save($sql, $DbName = 'bbs', $binds)
    {
        self::setupDB($DbName);
        $con = new PDO('mysql:dbname=' . self::$DbName, self::$user);
        $stmt = $con->prepare($sql);
        $stmt->execute($binds);
    }
}
