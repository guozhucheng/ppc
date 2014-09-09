<?php
namespace cache;
require_once(__DIR__ . '/../cache/IDataCache.php');
require_once(__DIR__ . '/../cache/SimpleCache.php');

/**
 * 数据缓存抽象工厂类
 * Created by guozhucheng@baidu.com
 */
abstract class DataCacheFactory {
    /**
     * SimpleCache 类
     */
    const  SIMPLECACHE = 'SimpleCache';

    /**
     * 获取缓存对象实例
     * @param string     $cacheName
     * @param null|array $config
     * @return IDataCache SimpleCache
     */

    static function  createCache($cacheName, $config = null) {
        //目前仅实现了 SimpleCache一种缓存实现
        switch ($cacheName) {
            case self::SIMPLECACHE:
                return new SimpleCache($config);
            default:
                return new SimpleCache($config);
        }
    }
}