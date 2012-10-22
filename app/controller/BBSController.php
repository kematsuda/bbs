<?php

require_once '/home/work/bbs/app/controller/smarty_config.php';

Class BBSController
{
    const DEFAULT_ARTICLES = 20;
    public $base_dir_path = '/home/work/bbs/app/template/';
    public static $log_dir = null;

    public function __construct()
    {
        self::$log_dir = dirname(__FILE__) . '/../../log/';
    }

    public function show()
    {
        $template_file = $this->$base_dir_path . 'bbs.html';
        $thread_info = BBS::findAll(self::$log_dir);
        $params['thread_names'] = $thread_names;
        self::render($params, $template_file);
    }

    public function showThread($id)
    {
        $template_file = $base_dir_path . 'thread.html';
        $params = array();
        $thread_info = ThreadInfo::findById(self::$log_dir, $id);
        $params['thread_info'] = $thread_info;
        $count_articles = intval($thread_info['count_articles']);
        $first_article = Article::findOneResById(self::$log_dir, $id);
        $params['first_article'] = $first_article;
        if($count_articles <= self::DEFAULT_ARTICLES)
        {
            $offset = 0;
            $limit = $count_articles;
        }
        else
        {
            $offset = self::DEFAULT_ARTICLES - $count_articles;
            $limit = self::DEFAULT_ARTICLES;
        }
        $articles = Article::findAllById(self::$log_dir, $id, $offset, $limit);
        $params['articles'] = $articles;
        self::render($params, $template_file);
    }

    public function showThreadAll($id)
    {
        $template_file = $base_dir_path . 'thread.html';
        $params = array();
        $thread_info = ThreadInfo::findById(self::$log_dir, $id);
        $params['thread_info'] = $thread_info;
        $articles = Article::findAllById(self::$log_dir, $id);
        $params['articles'] = $articles;
        self::render($params, $template_file);
    }

    public static function render($params, $template_file)
    {
        $keys = array_keys($params);
        $smarty_config = new SmartyConfig();
        $smarty = $smarty_config->setup();
        foreach($keys as $key)
        {
            $smarty->assign($key, $params[$key]);
        }
        $smarty->display($template_file);
    }
}
