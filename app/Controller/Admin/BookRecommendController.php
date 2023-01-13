<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use App\Controller\AbstractController;
use App\Model\Book;
use App\Model\BookType;
use App\Model\BookRecommend;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Paginator\Paginator;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\View\RenderInterface;
use HyperfExt\Jwt\Contracts\JwtFactoryInterface;
use HyperfExt\Jwt\Contracts\ManagerInterface;
use HyperfExt\Jwt\Jwt;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use App\Middleware\PermissionMiddleware;

/**
 * @Controller
 * @Middleware(PermissionMiddleware::class)
 */
class BookRecommendController extends AbstractController
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

    public function __construct(ManagerInterface $manager, JwtFactoryInterface $jwtFactory, RenderInterface $render)
    {
        parent::__construct();
        $this->manager = $manager;
        $this->jwt = $jwtFactory->make();
        $this->render = $render;
    }

    /**
     * @RequestMapping(path="index", methods={"GET"})
     */
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        // 顯示幾筆
        $step = BookRecommend::PAGE_PER;
        $page = $request->input('page') ? intval($request->input('page'), 10) : 1;
        $bookTypeId = $request->input('book_type_id');
        $bookName = $request->input('book_name');

        // 撈取書籍類型資料
        $book_types = BookType::orderby("book_type_id")->get();

        // 撈取資料
        $query = BookRecommend::join('book', 'book_recommend.book_id', '=', 'book.book_id')->join('book_type', 'book.type', '=', 'book_type.book_type_id')->select('book.book_id', 'book_type.type_name', 'book.book_name', 'book.status', 'book.cover_img', 'book_recommend.book_recommend_id', 'book_recommend.create_time');

        if(! empty($bookTypeId)){
            $query = $query->where('book.type', $bookTypeId);
        }

        if(! empty($bookName)){
            $query = $query->where('book.book_name', $bookName);
        }
        
        $total = count($query->get());
        $books = $query->orderby("book.book_id")->offset(($page - 1) * $step)->limit($step)->get();
        $data['last_page'] = ceil($total / $step);
        if ($total == 0) {
            $data['last_page'] = 1;
        }
        $data['navbar'] = trans('default.book_control.book_recommend_control');
        $data['book_recommend_active'] = 'active';
        $data['total'] = $total;
        $data['book_types'] = $book_types;
        $data['bookTypeId'] = $bookTypeId;
        $data['bookName'] = $bookName;
        $data['datas'] = $books;
        $data['page'] = $page;
        $data['step'] = $step;
        $path = '/admin/book_recommend/index';
        $pageArr = [
            'book_type_id' => $bookTypeId,
            'book_name' => $bookName,
        ];
        $pageArr['page'] = $page + 1;
        $nextQuery = http_build_query($pageArr);
        $data['next'] = $path . '?' . $nextQuery;

        $pageArr['page'] = $page - 1;
        $prevQuery = http_build_query($pageArr);
        $data['prev'] = $path . '?' . $prevQuery;

        $paginator = new Paginator($books, $step, $page);

        $data['paginator'] = $paginator->toArray();
        return $this->render->render('admin.bookRecommend.index', $data);
    }

    /**
     * @RequestMapping(path="delete", methods={"POST"})
     */
    public function delete(RequestInterface $request, ResponseInterface $response): PsrResponseInterface
    {
        $book_id = $request->input('id', 0);
        $bookTypeId = $request->input('book_type_id');
        $bookName = $request->input('book_name');
        $page = $request->input('page') ? intval($request->input('page'), 10) : 1;

        // 刪除
        $query = BookRecommend::where('book_id', $book_id);
        $query->delete();

        $re_url = '/admin/book_recommend/index?';
        if(! empty($bookTypeId)){
            $re_url .= 'book_type_id='.$bookTypeId.'&';
        }
        if(! empty($bookName)){
            $re_url .= 'book_name='.$bookName.'&';
        }
        if(! empty($page) && $page != 1){
            $re_url .= 'page='.$page.'&';
        }
        return $response->redirect($re_url);
    }  
}
