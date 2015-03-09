<?php
namespace cache;

use SebastianBergmann\Exporter\Exception;

/**
 * 数据缓存抽象工厂类
 * 实现缓存对象获取的工厂,在不同的项目中,可以包含不同的缓存实现

 */
abstract class DataCacheFactory {
    /**
     * SimpleCache 类
     */
    const  SIMPLE_CACHE = 'SimpleCache';



    

    /**
     * 获取缓存对象实例
     * @param string     $cacheName 缓存实例的名称
     * @param null|array $config 缓存的配置信息
     * @return IDataCache SimpleCache 缓存IDataCache接口的实例
     */
    static function  createCache($cacheName, $config = null) {
        //目前仅实现了 SimpleCache一种缓存实现
        switch ($cacheName) {
            case self::SIMPLE_CACHE:
                return new SimpleCache($config);
            default:
                throw new Exception($cacheName . '缓存类未定义');
        }
    }
}