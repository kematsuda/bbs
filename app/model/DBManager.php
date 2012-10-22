<?php

class DBManager
{
    public static function q($sql, $binds=array())
    {
        $result = array();
        $con = new PDO('mysql:dbname=bbs','root','');
        $stmt = $con->prepare($sql);
        $stmt->execute($binds);
        return $stmt->fetchAll();
    }

    public static function save($sql, $binds)
    {
        $con = new PDO('mysql:dbname=bbs','root','');
        $stmt = $con->prepare($sql);
        $stmt->execute($binds);
    }
}