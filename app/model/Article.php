<?php // -*- encoding:utf-8 -*-

class Article {

    const MAX_ARTICLES = 1000;

    public static function findOneResById($log_dir, $thread_id, $limit = 1)
    {
        $sql =<<<SQL
SELECT
    *
FROM
    `article`
WHERE
    `thread_id` = ?
LIMIT
    $limit
SQL;
        try
        {
            return DBManager::q($sql, 'bbs', array($thread_id));
        }
        catch (Exception $e)
        {
            error_log(date("Y-m-d H:i:s") . ':' . $e->getMessage() . "\n", 3, $log_dir);
            error_log(date("Y-m-d H:i:s") . ':' . __CLASS__ . ": DB_Error Occured\n", 3, $log_dir);
            return false;
        }
    }

    public static function countArticles($log_dir, $thread_id)
    {
        $sql =<<<SQL
SELECT
    count(`id`) as `count`
FROM
    `article`
WHERE
    `thread_id` = ?
SQL;
        try
        {
            return DBManager::q($sql, 'bbs', array($thread_id));
        }
        catch (Exception $e)
        {
            error_log(date("Y-m-d H:i:s") . ':' . $e->getMessage() . "\n", 3, $log_dir);
            error_log(date("Y-m-d H:i:s") . ':' . __CLASS__ . ": DB_Error Occured\n", 3, $log_dir);
            return false;
        }

    }

    public static function findAllById($log_dir, $thread_id, $offset = 1, $limit = self::MAX_ARITICLES)
    {
        $sql =<<<SQL
SELECT
    *
FROM
    `article`
WHERE
    `thread_id` = ?
LIMIT
    $offset, $limit
SQL;
        try
        {
            return DBManager::q($sql, 'bbs', array($thread_id));
        }
        catch (Exception $e)
        {
            error_log(date("Y-m-d H:i:s") . ':' . $e->getMessage() . "\n", 3, $log_dir);
            error_log(date("Y-m-d H:i:s") . ':' . __CLASS__ . ": DB_Error Occured\n", 3, $log_dir);
            return false;
        }
    }

    public static function save($log_dir, $article)
    {
        $sql =<<<SQL
INSERT INTO
    `article`
SET
    `thread_id` = ?,
    `user_name` = ?,
    `mail` = ?,
    `body` = ?,
    `inserted_at` = now()
SQL;
        $binds = array($article['thread_id'],
                       $article['user_name'],
                       $article['mail'],
                       $article['body']);
        try
        {
            DBManager::save($sql, 'bbs', $binds);
            ThreadInfo::updateThread($log_dir, $article['thread_id']);
            return true;
        }
        catch (Exception $e)
        {
            error_log(date("Y-m-d H:i:s") . ':' . $e->getMessage() . "\n", 3, $log_dir);
            error_log(date("Y-m-d H:i:s") . ':' . __CLASS__ . ": DB_Error Occured\n", 3, $log_dir);
            return false;
        }
    }
}