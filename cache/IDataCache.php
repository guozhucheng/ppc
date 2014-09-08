<?php

namespace cache;
/**
 * 定义缓存接口，如需不同的缓存实现，可通过实现该接口来完成
 * Created by guozhucheng@baidu.com
 */
interface IDataCache {
    /**
     * 构造函数
     * @param string $cacheName 缓存实例名称
     */
    public function __construct($cacheName = 'default');

    /**
     * 添加缓存数据
     * @param string $key 缓存key
     * @param object $data 缓存数据
     * @param int    $duration 缓存时间（秒）
     * @return bool
     */
    public function  addData($key, $data, $duration);

    /**
     * 获取缓存数据
     * @param string $key 缓存key
     * @return object
     */
    public function getData($key);

    /**
     * 缓存是否存在
     * @param string $key 缓存key
     * @return bool
     */
    public function  hasKey($key);

    /**
     * 删除某个缓存key
     * @param string $key
     * @return bool
     */
    public function  delKey($key);
}