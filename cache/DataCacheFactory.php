<?php
namespace cache;

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
     * @param $cacheName
     * @return IDataCache SimpleCache
     */
    static function  createCache($cacheName, $config = null) {
        //todo 目前仅实现了 SimpleCache一种缓存实现s
        switch ($cacheName) {
            case self::SIMPLECACHE:
                return new SimpleCache($config);
            default:
                return new SimpleCache($config);
        }
    }
}