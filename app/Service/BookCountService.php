<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Service;

use App\Constants\ApiCode;
use App\Model\BookCount;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Redis\Redis;

class BookCountService
{
    public const CACHE_KEY = 'Book_count';

    public const TTL_A_DAY = 86400;

    protected Redis $redis;

    protected \Psr\Log\LoggerInterface $logger;

    public function __construct(Redis $redis, LoggerFactory $loggerFactory)
    {
        $this->redis = $redis;
        $this->logger = $loggerFactory->get('reply');
    }

    /**
     * 新增廣告點擊資料.
     * @throws \RedisException
     */
    public function insertClickData(string $ip, int $book_id, int $book_type_id): array
    {
        if (empty($book_id) || empty($book_type_id)) {
            $this->logger->error('未定義 book_id or book_type_id 關鍵字');
            return ['code' => ApiCode::BAD_MISS_VARIABLE, 'msg' => '未定義 book_id or book_type_id 關鍵字'];
        }

        $date = date('Ymd');
        $key = self::CACHE_KEY;
        $checkRedisKey = "{$key}:{$book_id}:{$date}:{$ip}:{$book_type_id}:check";

        if (! $this->redis->exists($checkRedisKey)) {
            // 同網站下的同廣告同IP 1天只計算點擊1次
            $this->redis->set($checkRedisKey, 1);
            $this->redis->expire($checkRedisKey, self::TTL_A_DAY);

            // 存入DB
            $model = new BookCount();
            $model->book_type_id = $book_type_id;
            $model->book_id = $book_id;
            $model->ip = $ip;
            
            if (! $model->save()) {
                $this->logger->alert('Book count table 未存成功 ');
                return ['code' => ApiCode::BAD_INSERT_DB, 'msg' => 'Book count table 未新增成功'];
            }

            return ['code' => ApiCode::OK, 'msg' => 'ok'];
        }
        return ['code' => ApiCode::BAD_INPUT_IP, 'msg' => '己有相同IP'];
    }
}
