<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use App\Controller\AbstractController;
use App\Middleware\PermissionMiddleware;
use App\Model\Book;
use App\Model\BookType;
use App\Model\BookCount;
use App\Traits\SitePermissionTrait;
use Carbon\Carbon;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Paginator\Paginator;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\View\RenderInterface;
use HyperfExt\Jwt\Contracts\JwtFactoryInterface;
use HyperfExt\Jwt\Contracts\ManagerInterface;
use HyperfExt\Jwt\Jwt;

/**
 * @Controller
 * @Middleware(PermissionMiddleware::class)
 */
class BookCountController extends AbstractController
{
    use SitePermissionTrait;

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
        $startTime = $request->input('start_time', Carbon::yesterday()->toDateString());
        $endTime = $request->input('end_time', Carbon::today()->toDateString());
        $typeId = $request->input('type_id');

        $dates = [];
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        $count = abs($end->diffInDays($start));
        for ($i = 0; $i <= $count; ++$i) {
            $day = $start->copy()->addDays($i)->toDateString();
            $dates[] = $day;
        }

        if (! empty($typeId)) {
            $booktypes = BookType::where('book_type_id', $typeId)->get();
        } else {
            $booktypes = BookType::get();
        }

        $result = [];
        $type_name = [];
        foreach ($booktypes as $booktype) {
            $query = BookCount::whereBetween('created_at', [$startTime, $endTime])
                ->where('book_type_id', $booktype->book_type_id)
                ->groupBy([Db::raw('date(created_at)')])
                ->orderBy('created_at')
                ->select(Db::raw('date(created_at) as click_date'), Db::raw('count(*) as total'));
            $query = $this->attachQueryBuilder($query);

            $models = $query->get();

            $data = [];
            foreach ($dates as $key => $value) {
                $data[$key] = 0;
                foreach ($models as $model) {
                    if ($value == $model->click_date) {
                        $data[$key] = $model->total;
                    }
                }
            }
            $type_name[] = $booktype->type_name;
            $result[] = $data;
        }

        $data['models'] = $result;
        $data['labels'] = $dates;
        $data['type_name'] = $type_name;
        $booktypes = BookType::orderby('book_type_id')->get();
        $data['booktypes'] = $booktypes;
        $data['start_time'] = $startTime;
        $data['end_time'] = $endTime;
        $data['type_id'] = $typeId;
        $data['navbar'] = trans('default.bookCount_control.bookCount_control');
        $data['book_count_active'] = 'active';
        return $this->render->render('admin.bookcount.index', $data);
    }
}
