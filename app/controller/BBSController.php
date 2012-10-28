<?php
require_once '/usr/local/lib/Smarty-3.1.12/libs/Smarty.class.php';

Class BBSController
{
    const DEFAULT_ARTICLES = 20;
    public $base_dir_path = null;
    public $log_dir = null;

    public function __construct()
    {
        $this->base_dir_path = dirname(__FILE__) . '/../templates/';
        $this->log_dir = dirname(__FILE__) . '/../../log/';
    }

    public function show()
    {
        $template_file = $this->base_dir_path . 'bbs.html';
        $thread_info = ThreadInfo::findAll($this->log_dir);
        $params['thread_names'] = self::getThreadNames($thread_info);
        self::render($params, $template_file);
    }

    public static function getThreadNames($thread_info)
    {
        $thread_names = array();
        foreach($thread_info as $info) {
            $thread_names[] = $info['thread_name'];
        }
        return $thread_names;
    }

    public function showThread($id, $request)
    {
        $template_file = $base_dir_path . 'thread.html';
        $success_flag = true;
        if(!is_null($request->getPost('article'))) {
            $success_flag = $this->insert($request);
        }
        if(!$success_flag) {
            $params['message'] = '投稿に失敗しました';
            $params['id'] = $id;
            self::render($params, $template_file);
        }
        else {
            $params = array();
            $thread_info = ThreadInfo::findById($this->log_dir, $id);
            $params['thread_info'] = $thread_info;
            $count_articles = intval($thread_info['count_articles']);
            $first_article = Article::findOneResById($this->log_dir, $id);
            $params['first_article'] = $first_article;
            if($count_articles <= self::DEFAULT_ARTICLES) {
                $offset = 0;
                $limit = $count_articles;
            }
            else {
                $offset = self::DEFAULT_ARTICLES - $count_articles;
                $limit = self::DEFAULT_ARTICLES;
            }
            $articles = Article::findAllById($this->log_dir, $id, $offset, $limit);
            $params['articles'] = $articles;
            self::render($params, $template_file);
        }
    }

    public function insert($id, $request)
    {
        $article = $request->getPost($request);
        $insert_article['thread_id'] = $id;
        $insert_article['user_name'] = $artist['user_name'];
        $insert_article['mail'] = $artist['mail'];
        $insert_article['body'] = $artist['body'];
        return Article::save($this->log_dir, $insert_article);
    }

    public function showThreadAll($id)
    {
        $template_file = $base_dir_path . 'thread.html';
        $params = array();
        $thread_info = ThreadInfo::findById($this->log_dir, $id);
        $params['thread_info'] = $thread_info;
        $articles = Article::findAllById($this->log_dir, $id);
        $params['articles'] = $articles;
        self::render($params, $template_file);
    }

    public static function render($params, $template_file)
    {
        $keys = array_keys($params);
        $smarty = new Smarty();
        $smarty->template_dir = dirname(__FILE__) . '/../templates/';
        $smarty->compile_dir  = dirname(__FILE__) . '/../../var/smarty/templates_c/';
        $smarty->config_dir   = dirname(__FILE__) . '/../../var/smarty/configs/';
        $smarty->cache_dir   = dirname(__FILE__) . '/../../var/smarty/cache/';
        foreach($keys as $key)
        {
            $smarty->assign($key, $params[$key]);
        }
        $smarty->display($template_file);
    }
}
