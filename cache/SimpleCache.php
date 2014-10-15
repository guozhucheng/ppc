<?php
namespace cache;


class SimpleCache implements IDataCache {
    private static $cacheItems = array();

    private $currentCacheItem;

    /**
     * 构造函数
     * @param string $cacheName 缓存实例名称
     */
    public function __construct($cacheName = 'default') {
        //查看缓存列表里是否存在$cacheName的实例,不存在则新创建一个
        if (!array_key_exists($cacheName, self::$cacheItems)) {
            $this->currentCacheItem = new CacheItem($cacheName);
            $cacheItems[$cacheName] = $this->currentCacheItem;
        }
    }

    /**
     * 添加缓存数据
     * @param string $key 缓存key
     * @param object $data 缓存数据
     * @param int    $duration 缓存时间（秒）
     * @return bool
     */
    public function  addData($key, $data, $duration) {
        return $this->currentCacheItem->addCache($key, $data, $duration);
    }

    /**
     * 获取缓存数据
     * @param string $key 缓存key
     * @return object
     */
    public function getData($key) {
        return $this->currentCacheItem->getCachInfo($key);
    }

    /**
     * 缓存是否存在
     * @param string $key 缓存key
     * @return bool
     */
    public function  hasKey($key) {
        return $this->currentCacheItem->hasKey($key);
    }

    /**
     * 删除某个缓存key
     * @param string $key
     * @return bool
     */
    public function  delKey($key) {
        return $this->currentCacheItem->removeKey($key);
    }
}
