<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use App\Controller\AbstractController;
use App\Model\Book;
use App\Model\BookContent;
use App\Model\BookType;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\View\RenderInterface;
use HyperfExt\Jwt\Contracts\JwtFactoryInterface;
use HyperfExt\Jwt\Contracts\ManagerInterface;
use HyperfExt\Jwt\Jwt;
use Hyperf\DbConnection\Db;
use Hyperf\Redis\Redis;

/**
 * @Controller
 */
class IndexController extends AbstractController
{
    /**
     * 提供了对 JWT 编解码、刷新和失活的能力。
     */
    protected ManagerInterface $manager;

    /**
     * 提供了从请求解析 JWT 及对 JWT 进行一系列相关操作的能力。
     */
    protected Jwt $jwt;

    protected RenderInterface $render;
    /**
     * @Inject
     */
    protected ValidatorFactoryInterface $validationFactory;

    /**
     * @Inject
     * @var \Hyperf\Contract\SessionInterface
     */
    private $session;

    public const CACHE_KEY = 'frontend';
    public const TTL_A_DAY = 86400;

    protected Redis $redis;

    public function __construct(ManagerInterface $manager, JwtFactoryInterface $jwtFactory, RenderInterface $render, Redis $redis)
    {
        parent::__construct();
        $this->manager = $manager;
        $this->jwt = $jwtFactory->make();
        $this->render = $render;
        $this->redis = $redis;
    }

    /**
     * @RequestMapping(path="home", methods={"GET"})
     * 首頁
     */
    public function home(RequestInterface $request, ResponseInterface $response)
    {
        $date = date('Ymd');
        $key = self::CACHE_KEY;
        $popularRedisKey = "{$key}:popular:{$date}";
        $studyRedisKey = "{$key}:study:{$date}";
        $bookUpdateRedisKey = "{$key}:bookUpdate:{$date}";
        $bookCreateRedisKey = "{$key}:bookcreate:{$date}";
        // 撈取資料
        // 热门小说推荐
        if ($this->redis->exists($popularRedisKey)) {
            $popular = json_decode($this->redis->get($popularRedisKey));
        }else{
            $query = Book::join('book_recommend', 'book_recommend.book_id', '=', 'book.book_id')->select('book.book_id', 'book.book_name', 'author', 'introduction', 'cover_img')->orderby("book.book_id",'desc')->limit(8);
            $popular = $query->get();

            // 設置redis
            $this->redis->set($popularRedisKey, json_encode($popular));
            $this->redis->expire($popularRedisKey, self::TTL_A_DAY);
        }
        // 阅读排行榜
        if ($this->redis->exists($studyRedisKey)) {
            $study = json_decode($this->redis->get($studyRedisKey));
        }else{
            $query = Book::join('book_type','book_type.book_type_id', '=', 'book.type')->select('book.book_id', 'book_type.type_name', 'book.book_name', 'book.author')->inRandomOrder()->limit(20);
            // $query = Book::join('book_type','book_type.book_type_id', '=', 'book.type')->select('book.book_id', 'book_type.type_name', 'book.book_name', 'book.author', Db::raw('(select count(*) from book_counts where book_id = book.book_id) as book_counts'))->groupby('book_counts')->limit(20);
        
            $study = $query->get();
            // 設置redis
            $this->redis->set($studyRedisKey, json_encode($study));
            $this->redis->expire($studyRedisKey, self::TTL_A_DAY);
        }
        // 最近更新
        if ($this->redis->exists($bookUpdateRedisKey)) {
            $book_update = json_decode($this->redis->get($bookUpdateRedisKey));
        }else{
            $query = Book::join('book_type','book_type.book_type_id', '=', 'book.type')->join('book_content','book_content.book_id', '=', 'book.book_id')->select('book.book_id', 'book_type.type_name', 'book.book_name', 'book.author', 'book.latest_chapter', Db::raw('max(book_content.book_content_id) as book_content_id'))->groupby('book.book_id')->orderby("book.update_time",'desc')->limit(20);
            $book_update = $query->get();
            // 設置redis
            $this->redis->set($bookUpdateRedisKey, json_encode($book_update));
            $this->redis->expire($bookUpdateRedisKey, self::TTL_A_DAY);
        }
        // 最新小说
        if ($this->redis->exists($bookCreateRedisKey)) {
            $book_create = json_decode($this->redis->get($bookCreateRedisKey));
        }else{
            $query = Book::join('book_type','book_type.book_type_id', '=', 'book.type')->select('book.book_id', 'book_type.type_name', 'book.book_name', 'book.author')->orderby("book.create_time",'desc')->limit(20);
            $book_create = $query->get();
            // 設置redis
            $this->redis->set($bookCreateRedisKey, json_encode($book_create));
            $this->redis->expire($bookCreateRedisKey, self::TTL_A_DAY);
        }

        $data['popular']=$popular;
        $data['study']=$study;
        $data['book_update']=$book_update;
        $data['book_create']=$book_create;
        $data['home_url']='/frontend/index/home';
        $data['library_url']='/frontend/index/library';
        $data['bookinfo_url']='/frontend/index/bookinfo?bookId=';
        $data['booksearch_url']='/frontend/index/booksearch';
        $data['search_url']='/frontend/index/search';
        $data['bookcontent_url']='/frontend/index/content?bookContentId=';
        $data['complete_url']='/frontend/index/complete';
        $data['rank_url']='/frontend/index/rank';
        $data['history_url']='/frontend/index/history';
        $data['historydelete_url']='/frontend/index/historydelete?bookId=';
        return $this->render->render('frontend.index', $data);
    }

    /**
     * @RequestMapping(path="library", methods={"GET"})
     * 書庫
     */
    public function library(RequestInterface $request, ResponseInterface $response)
    {
        $date = date('Ymd');
        $key = self::CACHE_KEY;
        $typelistRedisKey = "{$key}:typelist:{$date}";
        // 顯示幾筆
        $step = Book::FRONTEND_PAGE_PER;
        $page = $request->input('page') ? intval($request->input('page'), 10) : 1;
        $type_id = $request->input('typeId') ? intval($request->input('typeId'), 10) : 1;

        // 撈取小說類型
        if($this->redis->exists($typelistRedisKey)){
            $type_list = json_decode($this->redis->get($typelistRedisKey));
        }else{
            $type_list = BookType::orderby('book_type_id')->get();
            // 設置redis
            $this->redis->set($typelistRedisKey, json_encode($type_list));
            $this->redis->expire($typelistRedisKey, self::TTL_A_DAY);
        }

        // 撈取該類型小說資料
        $query = Book::join('book_content','book_content.book_id', '=', 'book.book_id')->select('book.book_id', 'book.book_name', 'book.author', 'book.word_num', 'book.latest_chapter', 'book.introduction', Db::raw('max(book_content.book_content_id) as book_content_id'), Db::raw('(select count(*) from book_counts where book_id = book.book_id) as book_counts'))->where('book.type',$type_id)->groupby('book.book_id');
        $total_book_num = count($query->get());
        $total_page_num = ceil($total_book_num / $step);
        $books = $query->offset(($page - 1) * $step)->limit($step)->get();
        
        // title
        foreach ($type_list as $key => $value) {
           if($value -> book_type_id == $type_id){
                $type_title_name = $value -> type_name;
           }
        }

        $data['type_title_name']="小说分类 - " . $type_title_name;
        $data['type_list']=$type_list;
        $data['total_page_num']=$total_page_num;
        $data['books']=$books;
        $data['page']=$page;
        $data['type_id']=$type_id;
        $data['home_url']='/frontend/index/home';
        $data['library_url']='/frontend/index/library';
        $data['bookinfo_url']='/frontend/index/bookinfo?bookId=';
        $data['search_url']='/frontend/index/search';
        $data['bookcontent_url']='/frontend/index/content?bookContentId=';
        $data['complete_url']='/frontend/index/complete';
        $data['rank_url']='/frontend/index/rank';
        $data['history_url']='/frontend/index/history';
        $data['historydelete_url']='/frontend/index/historydelete?bookId=';
        return $this->render->render('frontend.library', $data);
    }

    /**
     * @RequestMapping(path="bookinfo", methods={"GET"})
     * 小說詳細資料
     */
    public function bookinfo(RequestInterface $request, ResponseInterface $response)
    {
        $book_id = $request->input('bookId') ? intval($request->input('bookId'), 10) : 1;
        $page = $request->input('page') ? intval($request->input('page'), 10) : 1;
        $date = date('Ymd');
        $key = self::CACHE_KEY;
        $bookinfoRedisKey = "{$key}:bookinfo:{$book_id}:{$date}";

        // 撈取小說資料
        if($this->redis->exists($bookinfoRedisKey)){
            $book_info = json_decode($this->redis->get($bookinfoRedisKey));
        }else{
            $query = Book::join('book_type','book_type.book_type_id', '=', 'book.type')->join('book_content','book_content.book_id', '=', 'book.book_id')->select('book.book_id', 'book_type.type_name', 'book.book_name', 'book.author', 'book.latest_chapter', 'book.introduction', 'book.cover_img', 'book.word_num', 'book.status', 'book_content.book_content_id', 'book_content.chapter', 'book_type.book_type_id', 'book.update_time', Db::raw('(select max(book_content_id) from book_content where book_id = book.book_id) as max_book_content_id'))->where('book.book_id',$book_id)->orderby("book_content.chapter_num");
            $book_info = $query->get();
            // 設置redis
            $this->redis->set($bookinfoRedisKey, json_encode($book_info));
            $this->redis->expire($bookinfoRedisKey, self::TTL_A_DAY);
        }

        $data['book_name']=$book_info[0] -> book_name;
        $data['type_id']=$book_info[0] -> book_type_id;
        $data['type_name']=$book_info[0] -> type_name;
        $data['book_img']=$book_info[0]->cover_img;
        $data['author']=$book_info[0]->author;
        $data['introduction']=$book_info[0]->introduction;
        $data['latest_chapter']=$book_info[0]->latest_chapter;
        $data['word_num']=$book_info[0]->word_num;
        $data['status']=$book_info[0]->status;
        $data['book_content_id']=$book_info[0]->book_content_id;
        $data['last_book_content_id']=$book_info[0]->max_book_content_id;
        $data['update_time']=$book_info[0]->update_time;
        $data['page']=$page;
        $data['book_info']=$book_info;
        $data['home_url']='/frontend/index/home';
        $data['library_url']='/frontend/index/library';
        $data['bookinfo_url']='/frontend/index/bookinfo?bookId=';
        $data['bookcontent_url']='/frontend/index/content?bookContentId=';
        $data['search_url']='/frontend/index/search';
        $data['complete_url']='/frontend/index/complete';
        $data['rank_url']='/frontend/index/rank';
        $data['history_url']='/frontend/index/history';
        $data['historydelete_url']='/frontend/index/historydelete?bookId=';
        return $this->render->render('frontend.bookinfo', $data);
    }

    /**
     * @RequestMapping(path="content", methods={"GET"})
     * 小說章節文章
     */
    public function content(RequestInterface $request, ResponseInterface $response)
    {
        // $book_id = $request->input('bookId') ? intval($request->input('bookId'), 10) : 1;
        // $page = $request->input('page') ? intval($request->input('page'), 10) : 1;
        $book_content_id = $request->input('bookContentId') ? intval($request->input('bookContentId'), 10) : 1;
        $date = date('Ymd');
        $key = self::CACHE_KEY;
        $bookContentRedisKey = "{$key}:bookContent:{$book_content_id}:{$date}";

        // 撈取小說章節內容
        if($this->redis->exists($bookContentRedisKey)){
            $book_content = json_decode($this->redis->get($bookContentRedisKey));
        }else{
            $query = Book::join('book_type','book_type.book_type_id', '=', 'book.type')->join('book_content','book_content.book_id', '=', 'book.book_id')->select('book.book_id', 'book_type.type_name', 'book.book_name', 'book_content.book_content_id', 'book_content.chapter', 'book_type.book_type_id', 'book_content.content', 'book_content.chapter_num', 'book.author')->where('book_content.book_content_id',$book_content_id);
            $book_content = $query->first();
            // 設置redis
            $this->redis->set($bookContentRedisKey, json_encode($book_content));
            $this->redis->expire($bookContentRedisKey, self::TTL_A_DAY);
        }

        // 查詢前後章節ID 
        $book_id = $book_content->book_id;
        $chapter_num = $book_content->chapter_num;
        // 上一章
        if($chapter_num == 1){
            $book_content_id_pre = $book_content_id;
        }else{
            $query = BookContent::select('book_content_id')->where([
                ['book_id', '=', $book_id],
                ['chapter_num', '=', ($chapter_num - 1)],
            ])->first();
            $book_content_id_pre = !empty($query->book_content_id) ? $query->book_content_id : $book_content_id;
        }
        // 下一章
        $query = BookContent::select('book_content_id')->where([
            ['book_id', '=', $book_id],
            ['chapter_num', '=', ($chapter_num + 1)],
        ])->first();
        $book_content_id_next = !empty($query->book_content_id) ? $query->book_content_id : $book_content_id;

        // 儲存閱讀紀錄到session中
        if(!empty($this->session->get('book_history'))){
            $book_history = $this->session->get('book_history');
        }
        $book_history[$book_id] = array(
            'book_id' => $book_id,
            'book_name' => $book_content->book_name,
            'type_name' => $book_content->type_name,
            'author' => $book_content->author,
            'book_content_id' => $book_content_id,
            'chapter' => $book_content->chapter
        );
        $this->session->set('book_history',$book_history);

        $data['book_id']=$book_id;
        $data['book_type_id']=$book_content->book_type_id;
        $data['type_name']=$book_content->type_name;
        $data['book_name']=$book_content->book_name;
        $data['chapter']=$book_content->chapter;
        $data['book_content']=htmlspecialchar($book_content->content);
        $data['home_url']='/frontend/index/home';
        $data['library_url']='/frontend/index/library?typeId='.$book_content->book_type_id;
        $data['bookinfo_url']='/frontend/index/bookinfo?bookId='.$book_content->book_id;
        $data['bookcontent_url_pre']='/frontend/index/content?bookContentId='.$book_content_id_pre;
        $data['bookcontent_url_next']='/frontend/index/content?bookContentId='.$book_content_id_next;
        $data['search_url']='/frontend/index/search';
        $data['complete_url']='/frontend/index/complete';
        $data['rank_url']='/frontend/index/rank';
        $data['history_url']='/frontend/index/history';
        $data['historydelete_url']='/frontend/index/historydelete?bookId=';
        return $this->render->render('frontend.content', $data);
    }

    /**
     * @RequestMapping(path="search", methods={"GET"})
     * 搜尋頁
     */
    public function search(RequestInterface $request, ResponseInterface $response)
    {
        $date = date('Ymd');
        $key = self::CACHE_KEY;
        $typelistRedisKey = "{$key}:typelist:{$date}";
        // 顯示幾筆
        $step = Book::FRONTEND_PAGE_PER;
        $page = $request->input('page') ? intval($request->input('page'), 10) : 1;
        $type_id = $request->input('typeId') ? intval($request->input('typeId'), 10) : 1;


        // 撈取該類型小說資料
        $query = Book::join('book_recommend', 'book_recommend.book_id', '=', 'book.book_id')->join('book_content','book_content.book_id', '=', 'book.book_id')->select('book.book_id', 'book.book_name', 'author', 'introduction', 'cover_img', 'word_num', 'latest_chapter', Db::raw('max(book_content.book_content_id) as book_content_id'),Db::raw('(select count(*) from book_counts where book_id = book.book_id) as book_counts'))->groupby('book.book_id');
        // $query = Book::join('book_recommend', 'book_recommend.book_id', '=', 'book.book_id')->join('book_content',[
        //     ['book_content.book_id', '=', 'book.book_id'],['book_content.chapter', '=', 'book.latest_chapter']
        // ])->select('book.book_id', 'book.book_name', 'author', 'introduction', 'cover_img', 'latest_chapter',  'book_content.book_content_id');
        $books = $query->orderby("book.book_id",'desc')->limit(12)->get();

        $data['books']=$books;
        $data['page']=$page;
        $data['type_id']=$type_id;
        $data['search_title']='热门搜索推荐';
        $data['home_url']='/frontend/index/home';
        $data['library_url']='/frontend/index/library';
        $data['bookinfo_url']='/frontend/index/bookinfo?bookId=';
        $data['booksearch_url']='/frontend/index/booksearch';
        $data['search_url']='/frontend/index/search';
        $data['bookcontent_url']='/frontend/index/content?bookContentId=';
        $data['complete_url']='/frontend/index/complete';
        $data['rank_url']='/frontend/index/rank';
        $data['history_url']='/frontend/index/history';
        $data['historydelete_url']='/frontend/index/historydelete?bookId=';
        return $this->render->render('frontend.search', $data);
    }

    /**
     * @RequestMapping(path="booksearch", methods={"POST"})
     * 搜尋欄
     */
    public function booksearch(RequestInterface $request, ResponseInterface $response)
    {
        $page = $request->input('page') ? intval($request->input('page'), 10) : 1;
        $searchtype = $request->input('searchtype');
        $searchkey = $request->input('searchkey');
        $date = date('Ymd');
        $key = self::CACHE_KEY;
        $query = '';
        // $bookinfoRedisKey = "{$key}:bookinfo:{$book_id}:{$date}";

        // var_dump($searchtype);
        // var_dump($searchkey);
        
        if($searchtype == 'book_name'){
            // 1. 查詢book id
            $query = Book::select('book_id')->where('book_name', '=', $searchkey);
            $book = $query->first();
            // var_dump(empty($query->first()));
            if(empty($book)){
                // 查無book id
                $query = Book::join('book_content','book_content.book_id', '=', 'book.book_id')->select('book.book_id', 'book.book_name', 'book.author', 'book.introduction', 'book.cover_img', 'book.word_num', 'book.latest_chapter',Db::raw('max(book_content.book_content_id) as book_content_id'),Db::raw('(select count(*) from book_counts where book_id = book.book_id) as book_counts'))->where('book.book_name', 'like', '%'.$searchkey.'%')->groupby('book.book_id')->orderby("book.book_id",'desc');
            }else{
                // 查到book id
                $book_id = $book -> book_id;
                $searchRedisKey = "{$key}:booksearch:{$book_id}:{$date}";

                // 撈取小說資料
                if($this->redis->exists($searchRedisKey)){
                    $book_info = json_decode($this->redis->get($searchRedisKey));
                }else{
                    $query = Book::join('book_type','book_type.book_type_id', '=', 'book.type')->join('book_content','book_content.book_id', '=', 'book.book_id')->select('book.book_id', 'book_type.type_name', 'book.book_name', 'book.author', 'book.latest_chapter', 'book.introduction', 'book.cover_img', 'book.word_num', 'book.status', 'book_content.book_content_id', 'book_content.chapter', 'book_type.book_type_id', 'book.update_time')->where('book.book_id',$book_id)->orderby("book_content.chapter_num");
                    $book_info = $query->get();
                    // 設置redis
                    $this->redis->set($searchRedisKey, json_encode($book_info));
                    $this->redis->expire($searchRedisKey, self::TTL_A_DAY);
                }

                $data['book_name']=$book_info[0] -> book_name;
                $data['type_id']=$book_info[0] -> book_type_id;
                $data['type_name']=$book_info[0] -> type_name;
                $data['book_img']=$book_info[0]->cover_img;
                $data['author']=$book_info[0]->author;
                $data['introduction']=$book_info[0]->introduction;
                $data['latest_chapter']=$book_info[0]->latest_chapter;
                $data['word_num']=$book_info[0]->word_num;
                $data['status']=$book_info[0]->status;
                $data['book_content_id']=$book_info[0]->book_content_id;
                $data['update_time']=$book_info[0]->update_time;
                $data['book_info']=$book_info;
                $data['page']= $page;
                $data['home_url']='/frontend/index/home';
                $data['library_url']='/frontend/index/library';
                $data['bookinfo_url']='/frontend/index/bookinfo?bookId=';
                $data['bookcontent_url']='/frontend/index/content?bookContentId=';
                $data['search_url']='/frontend/index/search';
                $data['complete_url']='/frontend/index/complete';
                $data['rank_url']='/frontend/index/rank';
                $data['history_url']='/frontend/index/history';
                $data['historydelete_url']='/frontend/index/historydelete?bookId=';
                return $this->render->render('frontend.bookinfo', $data);
            }
        }else{
            // 查詢作者
            $query = Book::join('book_content','book_content.book_id', '=', 'book.book_id')->select('book.book_id', 'book.book_name', 'book.author', 'book.introduction', 'book.cover_img', 'book.word_num', 'book.latest_chapter',Db::raw('max(book_content.book_content_id) as book_content_id'),Db::raw('(select count(*) from book_counts where book_id = book.book_id) as book_counts'))->where('book.author', 'like', '%'.$searchkey.'%')->groupby('book.book_id');
        }

        // 查無book id
        $searchRedisKey = "{$key}:booksearch:{$searchtype}:{$searchkey}:{$date}";
        // 撈取小說資料
        if($this->redis->exists($searchRedisKey)){
            $book_info = json_decode($this->redis->get($searchRedisKey));
        }else{
            $book_info = $query->get();
            // 設置redis
            $this->redis->set($searchRedisKey, json_encode($book_info));
            $this->redis->expire($searchRedisKey, self::TTL_A_DAY);
        }

        $data['books']=$book_info;
        $data['page']=$page;
        $data['search_title']='搜索結果';
        $data['home_url']='/frontend/index/home';
        $data['library_url']='/frontend/index/library';
        $data['bookinfo_url']='/frontend/index/bookinfo?bookId=';
        $data['booksearch_url']='/frontend/index/booksearch';
        $data['search_url']='/frontend/index/search';
        $data['bookcontent_url']='/frontend/index/content?bookContentId=';
        $data['complete_url']='/frontend/index/complete';
        $data['rank_url']='/frontend/index/rank';
        $data['history_url']='/frontend/index/history';
        $data['historydelete_url']='/frontend/index/historydelete?bookId=';
        return $this->render->render('frontend.search', $data);
    }

    /**
     * @RequestMapping(path="complete", methods={"GET"})
     * 完本
     */
    public function complete(RequestInterface $request, ResponseInterface $response)
    {
        // 顯示幾筆
        $step = Book::FRONTEND_PAGE_PER;
        $page = $request->input('page') ? intval($request->input('page'), 10) : 1;
        $type_id = $request->input('typeId') ? intval($request->input('typeId'), 10) : 1;


        // 撈取該類型小說資料
        $query = Book::join('book_content','book_content.book_id', '=', 'book.book_id')->select('book.book_id', 'book.book_name', 'book.author', 'book.word_num', 'book.latest_chapter', 'book.introduction', Db::raw('max(book_content.book_content_id) as book_content_id'), Db::raw('(select count(*) from book_counts where book_id = book.book_id) as book_counts'))->where('book.status','全本')->groupby('book.book_id');
        $total_book_num = count($query->get());
        $total_page_num = ceil($total_book_num / $step);
        $books = $query->offset(($page - 1) * $step)->limit($step)->get();
        

        $data['type_title_name']="完本小说";
        $data['total_page_num']=$total_page_num;
        $data['books']=$books;
        $data['page']=$page;
        $data['type_id']=$type_id;
        $data['home_url']='/frontend/index/home';
        $data['library_url']='/frontend/index/library';
        $data['bookinfo_url']='/frontend/index/bookinfo?bookId=';
        $data['search_url']='/frontend/index/search';
        $data['bookcontent_url']='/frontend/index/content?bookContentId=';
        $data['complete_url']='/frontend/index/complete';
        $data['rank_url']='/frontend/index/rank';
        $data['history_url']='/frontend/index/history';
        $data['historydelete_url']='/frontend/index/historydelete?bookId=';
        return $this->render->render('frontend.complete', $data);
    }

    /**
     * @RequestMapping(path="rank", methods={"GET"})
     * 排行
     */
    public function rank(RequestInterface $request, ResponseInterface $response)
    {
        // 顯示幾筆
        $step = Book::FRONTEND_PAGE_PER;
        $page = $request->input('page') ? intval($request->input('page'), 10) : 1;
        $rank_type = $request->input('rank_type') ? intval($request->input('rank_type'), 10) : 1;

        $query = "";
        switch ($rank_type) {
            case 1:
                // 总点击榜
                $type_title_name = trans('default.frontend.total_hit_rank');
                $query = Book::join('book_content','book_content.book_id', '=', 'book.book_id')->select('book.book_id', 'book.book_name', 'book.author', 'book.word_num', 'book.latest_chapter', 'book.introduction', Db::raw('max(book_content.book_content_id) as book_content_id'),Db::raw('(select count(*) from book_counts where book_id = book.book_id) as book_counts'))->groupby('book.book_id')->orderby("book_counts",'desc');
                break;
            case 2:
                // 月点击榜
                $type_title_name = trans('default.frontend.month_hit_rank');
                $query = Book::join('book_content','book_content.book_id', '=', 'book.book_id')->select('book.book_id', 'book.book_name', 'book.author', 'book.word_num', 'book.latest_chapter', 'book.introduction', Db::raw('max(book_content.book_content_id ) as book_content_id'),Db::raw('(select count(*) from book_counts where book_id = book.book_id and YEAR(created_at)=YEAR(now()) and MONTH(created_at)=MONTH(now())) as book_counts'))->groupby('book.book_id')->orderby("book_counts",'desc');
                break;
            case 3:
                // 周点击榜
                $type_title_name = trans('default.frontend.week_hit_rank');
                $query = Book::join('book_content','book_content.book_id', '=', 'book.book_id')->select('book.book_id', 'book.book_name', 'book.author', 'book.word_num', 'book.latest_chapter', 'book.introduction', Db::raw('max(book_content.book_content_id) as book_content_id'),Db::raw('(select count(*) from book_counts where book_id = book.book_id  and YEAR(created_at)=YEAR(now()) and week(created_at)=week(now())) as book_counts'))->groupby('book.book_id')->orderby("book_counts",'desc');
                break;
            case 4:
                // 最新入库
                $type_title_name = trans('default.frontend.latest_storage_rank');
                $query = Book::join('book_content','book_content.book_id', '=', 'book.book_id')->select('book.book_id', 'book.book_name', 'book.author', 'book.word_num', 'book.latest_chapter', 'book.introduction', Db::raw('max(book_content.book_content_id) as book_content_id'),Db::raw('(select count(*) from book_counts where book_id = book.book_id) as book_counts'))->groupby('book.book_id')->orderby("book.create_time",'desc');
                break;
            case 5:
                // 最近更新
                $type_title_name = trans('default.frontend.book_update');
                $query = Book::join('book_content','book_content.book_id', '=', 'book.book_id')->select('book.book_id', 'book.book_name', 'book.author', 'book.word_num', 'book.latest_chapter', 'book.introduction', Db::raw('max(book_content.book_content_id) as book_content_id'),Db::raw('(select count(*) from book_counts where book_id = book.book_id) as book_counts'))->groupby('book.book_id')->orderby("book.update_time",'desc');
                break;    
            default:
                // 总点击榜
                $type_title_name = trans('default.frontend.total_hit_rank');
                $query = Book::join('book_content','book_content.book_id', '=', 'book.book_id')->select('book.book_id', 'book.book_name', 'book.author', 'book.word_num', 'book.latest_chapter', 'book.introduction', Db::raw('max(book_content.book_content_id) as book_content_id'),Db::raw('(select count(*) from book_counts where book_id = book.book_id) as book_counts'))->groupby('book.book_id')->orderby("book_counts",'desc');
                break;
        }
        $total_book_num = count($query->get());
        $total_page_num = ceil($total_book_num / $step);
        $books = $query->offset(($page - 1) * $step)->limit($step)->get();

        $data['type_title_name']=$type_title_name;
        $data['total_page_num']=$total_page_num;
        $data['books']=$books;
        $data['page']=$page;
        $data['rank_type']=$rank_type;
        $data['home_url']='/frontend/index/home';
        $data['library_url']='/frontend/index/library';
        $data['bookinfo_url']='/frontend/index/bookinfo?bookId=';
        $data['search_url']='/frontend/index/search';
        $data['bookcontent_url']='/frontend/index/content?bookContentId=';
        $data['complete_url']='/frontend/index/complete';
        $data['rank_url']='/frontend/index/rank';
        $data['history_url']='/frontend/index/history';
        $data['historydelete_url']='/frontend/index/historydelete?bookId=';
        return $this->render->render('frontend.rank', $data);
    }

    /**
     * @RequestMapping(path="history", methods={"GET"})
     * 閱讀歷史紀錄
     */
    public function history(RequestInterface $request, ResponseInterface $response)
    {
        $history = $this->session->get('book_history');

        $history_books = [];
        foreach ($history as $key => $data) {
            array_push($history_books, $data);

        }

        $data['type_title_name']="阅读记录";
        $data['books']=$history_books;
        $data['home_url']='/frontend/index/home';
        $data['library_url']='/frontend/index/library';
        $data['bookinfo_url']='/frontend/index/bookinfo?bookId=';
        $data['search_url']='/frontend/index/search';
        $data['bookcontent_url']='/frontend/index/content?bookContentId=';
        $data['complete_url']='/frontend/index/complete';
        $data['rank_url']='/frontend/index/rank';
        $data['history_url']='/frontend/index/history';
        $data['historydelete_url']='/frontend/index/historydelete?bookId=';
        return $this->render->render('frontend.history', $data);
    }

    /**
     * @RequestMapping(path="historydelete", methods={"GET"})
     * 刪除閱讀歷史紀錄
     */
    public function historydelete(RequestInterface $request, ResponseInterface $response)
    {
        $book_id = $request->input('bookId');
        
        $history = $this->session->get('book_history');
        $history_books = [];
        $re_history = [];
        foreach ($history as $key => $data) {
            if($key == $book_id) continue;
            // 給前端
            array_push($history_books, $data);
            // 更新閱讀紀錄
            $re_history[$key] = $data;
        }
        $this->session->forget('book_history');
        $this->session->set('book_history', $re_history);

        $data['type_title_name']="阅读记录";
        $data['books']=$history_books;
        $data['home_url']='/frontend/index/home';
        $data['library_url']='/frontend/index/library';
        $data['bookinfo_url']='/frontend/index/bookinfo?bookId=';
        $data['search_url']='/frontend/index/search';
        $data['bookcontent_url']='/frontend/index/content?bookContentId=';
        $data['complete_url']='/frontend/index/complete';
        $data['rank_url']='/frontend/index/rank';
        $data['history_url']='/frontend/index/history';
        $data['historydelete_url']='/frontend/index/historydelete?bookId=';
        return $this->render->render('frontend.history', $data);
    }
}
