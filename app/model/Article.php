<?php // -*- encoding:utf-8 -*-

class Article {

    const MAX_ARTICLES = 1000;

    protected function findOneResById($log_dir, $thread_id, $limit = 1)
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
            return DBManager::q($sql);
        }
        catch (Exception $e)
        {
            error_log(date("Y-m-d h:i:s") . __CLASS__ . ": DB_Error Occured", 2, $log_dir);
            return false;
        }
    }

    protected function countArticles($log_dir, $thread_id)
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
            return DBManager::q($sql, array($thread_id));
        }
        catch (Exception $e)
        {
            error_log(date("Y-m-d h:i:s") . __CLASS__ . ": DB_Error Occured", 2, $log_dir);
            return false;
        }

    }

    protected function findAllById($log_dir, $thread_id, $offset = 1, $limit = self::MAX_ARITICLES)
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
            return DBManager::q($sql);
        }
        catch (Exception $e)
        {
            error_log(date("Y-m-d h:i:s") . __CLASS__ . ": DB_Error Occured", 2, $log_dir);
            return false;
        }
    }

    protected function save($log_dir, $article)
    {
        $sql =<<<SQL
INSERT INTO
    `article`
SET
    `id` = ?,
    `thread_id` = ?,
    `user_name` = ?,
    `body` = ?,
    `inserted_at` = now()
SQL;
        $binds = array($article['id'],
                       $article['thread_id'],
                       $article['user_name'],
                       $article['body']);
        try
        {
            DBManager::save($sql, $binds);
            ThreadInfo::updateThread($log_dir, $article['thread_id']);
            return true;
        }
        catch (Exception $e)
        {
            error_log(date("Y-m-d h:i:s") . __CLASS__ . ": DB_Error Occured where thread was created", 2, $log_dir);
            return false;
        }


    }

    public function deleteAllArticles($log_dir, $thread_id)
    {
        $sql =<<<SQL
DELETE FROM
    `article`
WHERE
    `thread_id` = ?
SQL;
        try
        {
            DBManager::save($sql, $array($thread_id));
            return true;
        }
        catch (Exception $e)
        {
            error_log(date("Y-m-d h:i:s") . __CLASS__ . ": DB_Error Occured when thread was deleted", 2, $log_dir);
            return false;
        }
    }
}