<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/7
 * Time: 11:32
 */

namespace sinri\ark\web;


use Predis\Session\Handler;
use sinri\ark\database\redis\ArkRedis;

class ArkWebSessionWithRedis
{
    /**
     * A special entrance with Redis @uses \Predis\Session\Handler
     * Instantiate a new client just like you would normally do. Using a prefix for
     * keys will effectively prefix all session keys with the specified string.
     * @param ArkRedis $redisAgent such as new Client($single_server, array('prefix' => 'sessions:'));
     * @param int $sessionLifetime
     */
    public static function sessionStartWithRedis($redisAgent, $sessionLifetime = 3600)
    {
        $options = ['gc_maxlifetime' => $sessionLifetime];
        $handler = new class($redisAgent->getRedisClient(), $options) extends Handler
        {
            /**
             * @var string
             */
            protected $session_id;

            /**
             * @var string
             */
            protected $session_name;

            /**
             * @return string
             */
            public function getSessionId(): string
            {
                return $this->session_id;
            }

            /**
             * @param string $session_id
             */
            public function setSessionId(string $session_id)
            {
                $this->session_id = $session_id;
            }

            /**
             * @return string
             */
            public function getSessionName(): string
            {
                return $this->session_name;
            }

            /**
             * @param string $session_name
             */
            public function setSessionName(string $session_name)
            {
                $this->session_name = $session_name;
            }
        };

        // Register the session handler.
        $handler->register();

        session_start();

        //获取当前会话 ID
        $session_id = session_id();
        $handler->setSessionID($session_id);
        //读取会话名称
        $session_name = session_name();
        $handler->setSessionName($session_name);
    }
}