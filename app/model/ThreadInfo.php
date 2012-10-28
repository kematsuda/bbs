<?php

class ThreadInfo
{
    const FIND_LIMIT = 20;

    public static function findAll($log_dir, $offset = 0, $limit = self::FIND_LIMIT)
    {
        $sql =<<< SQL
SELECT
    `id`, `thread_name`
FROM
    thread_info
ORDER BY
    `updated_at` DESC
LIMIT
    $offset, $limit
SQL;
        try
        {
           return DBManager::q($sql);
        }
        catch (Exception $e)
        {
            error_log(date("Y-m-d H:i:s") . ':' . $e->getMessage() . "\n", 3, $log_dir);
            error_log(date("Y-m-d H:i:s") . ':' . __CLASS__ . ": DB_Error Occured\n", 3, $log_dir);
            return false;
        }
    }


    public static function findById($log_dir, $id)
    {
        $sql =<<< SQL
SELECT
    `id`, `thread_name`, `count_articles`
FROM
    `thread_info`
WHERE
    `id` = ?
SQL;
        try
        {
            return DBManager::q($sql, 'bbs', array($id));
        }
        catch (Exception $e)
        {
            error_log(date("Y-m-d H:i:s") . ':' . $e->getMessage() . "\n", 3, $log_dir);
            error_log(date("Y-m-d H:i:s") . ':' . __CLASS__ . ": DB_Error Occured\n", 3, $log_dir);
            return false;
        }
    }


    public static function createThread($log_dir, $thread_name)
    {
        $sql =<<< SQL
INSERT INTO
    `thread_info`
SET
    `thread_name` = ?,
    `updated_at` = now(),
    `count_articles` = 0
SQL;
        try
        {
            DBManager::save($sql, 'bbs' ,array($thread_name));
            return true;
        }
        catch (Exception $e)
        {
            error_log(date("Y-m-d H:i:s") . ':' . $e->getMessage() . "\n", 3, $log_dir);
            error_log(date("Y-m-d H:i:s") . ':' . __CLASS__ . ": DB_Error Occured\n", 3, $log_dir);
            return false;
        }
    }

    public static function updateThread($log_dir, $id)
    {
        $sql =<<< SQL
UPDATE
    `thread_info`
SET
    `count_articles` = ?,
    `updated_at` = now()
WHERE
    `id` = ?
SQL;
        try
        {
            $thread_info = self::findById($log_dir, $id);
            $count_articles = intval($thread_info[0]['count_articles']) + 1;
            DBManager::save($sql, 'bbs', array($count_articles, $id));
            return true;
        }
        catch (Exception $e)
        {
            error_log(date("Y-m-d H:i:s") . ':' . $e->getMessage() . "\n", 3, $log_dir);
            error_log(date("Y-m-d H:i:s") . ':' . __CLASS__ . ": DB_Error Occured\n", 3, $log_dir);
            return false;
        }

    }

    public static function findLatestThreadId($log_dir)
    {
                $sql =<<< SQL
SELECT
    `id`, `thread_name`
FROM
    thread_info
ORDER BY
    `id` DESC
LIMIT
     1
SQL;
        try
        {
           $result = DBManager::q($sql);
           return $result[0]['id'];
        }
        catch (Exception $e)
        {
            error_log(date("Y-m-d H:i:s") . ':' . $e->getMessage() . "\n", 3, $log_dir);
            error_log(date("Y-m-d H:i:s") . ':' . __CLASS__ . ": DB_Error Occured\n", 3, $log_dir);
            return false;
        }

    }
}
