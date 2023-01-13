<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use App\Service\BookCountService;
use App\Service\ObfuscationService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use App\Constants\ApiCode;
use OpenApi\Annotations as OA;

/**
 * @Controller
 * @OA\Info(title="阿古小說 API", version="1.0")
 */
class BookCountController
{
    /**
     * @RequestMapping(path="click", methods="post")
     * 
     * @OA\Post(
     *     path="/api/book_count/click",
     *     tags={""},
     *     summary="",
     *     description="",
     *     operationId="",
     *     @OA\Parameter(name="Authorization", in="header", description="JWT Token", required=true,
     *         @OA\Schema(type="string", default="Bearer {{Authorization}}")
     *     ),
     *     @OA\RequestBody(description="請求body",
     *         @OA\JsonContent(type="object",
     *             required={"book_id","book_type_id"},
     *             @OA\Property(property="book_id", type="integer", description="小說ID"),
     *             @OA\Property(property="book_type_id", type="integer", description="小說類型ID"),
     *         )
     *     ),
     *     @OA\Response(response="200", description="返回響應資料",
     *         @OA\JsonContent(type="object",
     *             required={"errcode","timestamp","data"},
     *             @OA\Property(property="errcode", type="integer", description="錯誤碼"),
     *             @OA\Property(property="timestamp", type="integer", description=""),
     *             @OA\Property(property="data", type="object", description="返回資料",
     *                 required={"msg"},
     *                 @OA\Property(property="msg", type="string", description=""),
     *             ),
     *         )
     *     )
     * )
     */
    public function click(RequestInterface $request, ObfuscationService $response, BookCountService $service)
    {
        $ip = getIp($request->getHeaders(), $request->getServerParams());
        $book_id = $request->input('book_id');
        $book_type_id = $request->input('book_type_id');
        $res = $service->insertClickData($ip, (int) $book_id, (int) $book_type_id);

        $data['msg'] = $res['msg'];
        if ($res['code'] == ApiCode::OK) {
            return $response->replyData($data);
        }
        return $response->replyData($data, $res['code']);
    }
}
