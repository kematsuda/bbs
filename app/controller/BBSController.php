<?php //-*- encoding:utf-8 -*-

require_once '/usr/local/lib/Smarty-3.1.12/libs/Smarty.class.php';

Class BBSController
{
    const DEFAULT_ARTICLES = 20;
    public $base_dir_path = null;
    public $log_dir = null;

    public function __construct()
    {
        $this->base_dir_path = dirname(__FILE__) . '/../templates/';
        $this->log_dir = dirname(__FILE__) . '/../../log/error.log';
    }

    public function show()
    {
        $template_file = $this->base_dir_path . 'bbs.html';
        $thread_info = ThreadInfo::findAll($this->log_dir);
        $params['thread_info'] = $thread_info;
        self::render($params, $template_file);
    }

    public function showThread($id, $request)
    {
        $template_file = $this->base_dir_path . 'thread.html';
        $success_flag = true;
        $thread_info = ThreadInfo::findById($this->log_dir, $id);
        if(!is_null($request->getPost('article')) && intval($thread_info[0]['count_articles']) < 1000) {
            $article = $request->getPost('article');
            $success_flag = $this->insert($id, $article, $thread_info[0]['count_articles']);
        }
        if(!$success_flag) {
            $params['message'] = '投稿に失敗しました';
            $params['id'] = $id;
            self::render($params, $template_file);
        }
        else {
            $params = array();
            $thread_info = ThreadInfo::findById($this->log_dir, $id);
            $params['thread_info'] = $thread_info[0];
            $count_articles = intval($thread_info[0]['count_articles']);
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
            if($count_articles >=1000) {
                $articles[] = self::addLastArticle();
            }
            $params['articles'] = $articles;
            self::render($params, $template_file);
        }
    }

    public static function addLastArticle()
    {
        $article = array();
        $article['user_name'] = '名無しさん';
        $article['mail'] = 'sage';
        $article['body'] = 'このスレッドは1000を越えました・・・\n次のスレッドを立ててください。';
        $article['article_no'] = 1001;
        return $article;
    }

    public function insert($id, $article, $article_no)
    {
        $insert_article['thread_id'] = $id;
        $insert_article['user_name'] = $article['user_name'];
        $insert_article['mail'] = $article['mail'];
        $insert_article['body'] = $article['body'];
        $insert_article['article_no'] = intval($article_no) +1;
        return Article::save($this->log_dir, $insert_article);
    }

    public function showThreadAll($id)
    {
        $template_file = $this->base_dir_path . 'thread.html';
        $params = array();
        $thread_info = ThreadInfo::findById($this->log_dir, $id);
        $params['thread_info'] = $thread_info;
        $articles = Article::findAllById($this->log_dir, $id);
        $params['articles'] = $articles;
        self::render($params, $template_file);
    }

    public function create($request)
    {
        $requests = array();
        if(!is_null($request->getPost('subject'))) {
            $subject = $request->getPost('subject');
            $requests['user_name'] = $request->getPost('name');
            $requests['mail'] = $request->getPost('mail');
            $requests['body'] = $request->getPost('body');
            ThreadInfo::createThread($this->log_dir, $subject);
            $id = ThreadInfo::findLatestThread();
            $this->insert($id, $requests, 0);
            $this->show();
        }
        else {
            $template_file = $this->base_dir_path . 'create_thread.html';
            self::render(array(), $template_file);
        }

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
