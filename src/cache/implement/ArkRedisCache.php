<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/7
 * Time: 10:28
 */

namespace sinri\ark\cache\implement;


use Predis\Client;
use sinri\ark\cache\ArkCache;
use sinri\ark\database\redis\ArkRedis;

class ArkRedisCache implements ArkCache
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * ArkRedisCache constructor.
     * @param string $host
     * @param int $port
     * @param int $database
     * @param null $password
     */
    public function __construct($host, $port = 6379, $database = 255, $password = null)
    {
        $this->client = (ArkRedis::simpleParameterBuilder($host, $port, $database, $password))->$this->getClient();
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string $key
     * @param mixed $object
     * @param int $life 0 for no limit, or seconds
     * @return bool
     */
    public function saveObject($key, $object, $life = 0)
    {
        if ($life > 0) {
            return $this->client->setex($key, $life, $object);
        }
        return $this->client->set($key, $object);
    }

    /**
     * @param string $key
     * @return string|bool
     */
    public function getObject($key)
    {
        return $this->client->get($key);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function removeObject($key)
    {
        return $this->client->del([$key]);
    }

    /**
     * @return bool
     */
    public function removeExpiredObjects()
    {
        // REDIS WOULD DO THIS...
        return true;
    }

    /**
     * @param string $key
     * @param int $by
     */
    public function increase($key, $by = 1)
    {
        $this->client->incrby($key, $by);
    }

    /**
     * @param string $key
     * @param int $by
     */
    public function decrease($key, $by = 1)
    {
        $this->client->decrby($key, $by);
    }

    /**
     * @param string $key
     * @param float $by
     */
    public function increaseFloat($key, $by = 1.0)
    {
        $this->client->incrbyfloat($key, $by);
    }

    /**
     * 如果 key 已经存在并且是一个字符串， APPEND 命令将 $tail 追加到 key 原来的值的末尾。
     * @param string $key
     * @param string $tail
     */
    public function append($key, $tail)
    {
        $this->client->append($key, $tail);
    }
}