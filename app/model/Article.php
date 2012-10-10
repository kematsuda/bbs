<?php // -*- encoding:utf-8 -*-

class Article {

    protected $record = array();
    private static $read_properties = array('thread_id',
                                            'user_id',
                                            'user_name',
                                            'title',
                                            'body');

    private static $write_properties = array('thread_id',
                                             'user_id',
                                             'user_name',
                                             'title',
                                             'body');

    public function __construct($record)
    {
        $this->record = $record;
    }

    protected function __set($name, $value)
    {
        if(array_search($name, self::$write_properties) !== false)
        {
            $this->record[$name] = $value;
        }
    }

    protected function __get($name)
    {
        return $this->record[$name];
    }

    //記事を取得してくるメソッド
    protected function findAll($offset = 0, $limit = self::FIND_LIMIT)
    {
        $sql =<<<SQL
SELECT
    *
FROM
    `article`
LIMIT
    $offset, $limit
SQL;
    }

    protected function findById($id)
    {
        $sql =<<<SQL
SELECT
    *
FROM
    `article`
WHERE
    `id` = $id
SQL;
    }

    protected function save($article)
    {
        $sql =<<<SQL
INSERT INTO
    `article`
SET
    `id` = ?,
    `user_id` = ?,
    `user_name` = ?,
    `title` = ?,
    `body` = ?
SQL;
    }

    protected function update($article)
    {
    }
}