<?php

require dirname(__FILE__) . '/DBManager.php';

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
ORDER_BY
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
            error_log(date("Y-m-d h:i:s") . __CLASS__ . ": DB_Error Occured", 3, $log_dir);
            return false;
        }
    }


    public static function findById($log_dir, $id)
    {
        $sql =<<< SQL
SELECT
    `id`, `thread_name`
FROM
    `thread_info`
WHERE
    `id` = ?
SQL;
        try
        {
            return DBManager::q($sql, array($id));
        }
        catch (Exception $e)
        {
            error_log(date("Y-m-d h:i:s") . __CLASS__ . ": DB_Error Occured", 2, $log_dir);
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
    `updated_at` = now()
SQL;
        try
        {
            DBManager::save($sql, $array($thread_name));
            return true;
        }
        catch (Exception $e)
        {
            error_log(date("Y-m-d h:i:s") . __CLASS__ . ": DB_Error Occured where thread was created", 2, $log_dir);
            return false;
        }
    }

    public static function updateThread($log_dir, $id)
    {
        $sql =<<< SQL
UPDATE
    `thread_info`
SET
    `count_articles` = ?
    `updated_at` = now()
WHERE
    `id` = ?
SQL;
        try
        {
            $article = Article::countArticles($log_dir, $id);
            $count_articles = $article['count'];
            DBManager::save($sql, $array($id, $count_articles));
            return true;
        }
        catch (Exception $e)
        {
            error_log(date("Y-m-d h:i:s") . __CLASS__ . ": DB_Error Occured where thread was created", 2, $log_dir);
            return false;
        }

    }

    public static function deleteThread($log_dir, $id)
    {
        $sql =<<< SQL
DELETE FROM
    `thread_info`
WHERE
    `id` = ?
SQL;
        try
        {
            DBManager::save($sql, $array($id));
            ARTICLE::deleteAllArticles($log_dir, $id);
            return true;
        }
        catch (Exception $e)
        {
            error_log(date("Y-m-d h:i:s") . __CLASS__ . ": DB_Error Occured when thread was deleted", 2, $log_dir);
            return false;
        }
    }
}
