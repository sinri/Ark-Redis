<?php
/**
 * Created by PhpStorm.
 * User: Sinri
 * Date: 2018/9/7
 * Time: 11:46
 */

$cache = new \sinri\ark\cache\implement\ArkRedisCache("redis.sample.com", 6379, 255, "password");

$cache->append("key", "value_appended_to_the_tail_of_old_value");
$cache->increase("key", 1);
$cache->decrease("key", 1);
$cache->increaseFloat("key", 0.5);