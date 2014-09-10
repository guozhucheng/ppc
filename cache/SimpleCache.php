<?php
namespace cache;

use Exception;

/**
 * 基于文件实现的缓存
 * Class SimpleCache
 */
class SimpleCache implements IDataCache {
    /**
     * 缓存路径 默认 simple-cache
     * @var string
     */
    private $_cachePath = 'simple-cachefile/';

    /**
     *缓存名称
     * @var string
     */
    private $_cachename = 'default';

    /**
     * 缓存扩展名
     * @var string
     */
    private $_extension = '.cache';

    /**
     * 加载缓存
     * @return mixed
     */
    private function loadCacheInfo() {
        if (true === file_exists($this->getCacheDir())) {
            $file = file_get_contents($this->getCacheDir());

            return json_decode($file, true);
        } else {
            return false;
        }
    }

    /**
     * 获取缓存目录路径
     * @return string
     */
    private function getCacheDir() {
        if (true === $this->_checkCacheDir()) {
            $filename = $this->_cachename;
            $filename = preg_replace('/[^0-9a-z\.\_\-]/i', '', strtolower($filename));

            return $this->_cachePath . $this->_getHash($filename) . $this->_extension;
        }
    }

    /**
     * 构造函数
     * @param null|array $config
     */
    public function __construct($config = null) {
        if (true === isset($config)) {
            if (is_string($config)) {
                $this->_cachename = $config;
            } else if (is_array($config)) {
                $this->_cachename = $config['name'];
                $this->_cachePath = $config['path'];
                $this->_extension = $config['extension'];
            }
        }
    }

    /**
     * 移除过期的缓存
     * @return int
     */
    private function eraseExpired() {
        $cacheData = $this->loadCacheInfo();
        if (true === is_array($cacheData)) {
            $counter = 0;
            foreach ($cacheData as $key => $entry) {
                if (true === $this->_checkExpired($entry['time'], $entry['expire'])) {
                    unset($cacheData[$key]);
                    $counter++;
                }
            }
            if ($counter > 0) {
                $cacheData = json_encode($cacheData);
                file_put_contents($this->getCacheDir(), $cacheData);
            }

            return $counter;
        }
    }


    /**
     * 删除所有的缓存
     * @return $this
     */
    public function delAll() {
        $cacheDir = $this->getCacheDir();
        if (true === file_exists($cacheDir)) {
            $cacheFile = fopen($cacheDir, 'w');
            fclose($cacheFile);
        }

        return $this;
    }


    /**
     * 获取名称hash
     * @param $filename
     * @return string
     */
    private function _getHash($filename) {
        return sha1($filename);
    }

    /**
     * 检查是否过期
     * @param int $timestamp 时间戳
     * @param int $expiration 过期时间（秒）
     * @return bool
     */
    private function _checkExpired($timestamp, $expiration) {
        $result = false;
        if ($expiration !== 0) {
            $timeDiff = time() - $timestamp;
            ($timeDiff > $expiration) ? $result = true : $result = false;
        }

        return $result;
    }

    /**
     * 检查缓存中的目录是否可写，如果目录不存在就创建一个
     * @return bool
     * @throws Exception
     */
    private function _checkCacheDir() {
       var_dump($this->_cachePath);
        if (!is_dir($this->_cachePath) && !mkdir($this->_cachePath, 0775, true)) {
            throw new Exception('无法创建缓存目录' . $this->_cachePath);
        } elseif (!is_readable($this->_cachePath) || !is_writable($this->_cachePath)) {
            if (!chmod($this->_cachePath, 0775)) {
                throw new Exception($this->_cachePath . ' 目录必须可读可写');
            }
        }

        return true;
    }

    /**
     * 添加缓存数据
     * @param string $key 缓存key
     * @param object $data 缓存数据
     * @param int    $duration 缓存时间（秒）
     * @return bool
     */
    public function  addData($key, $data, $duration) {
        $storeData = array(
            'time' => time(), 'expire' => $duration, 'data' => serialize($data)
        );
        $dataArray = $this->loadCacheInfo();
        if (true === is_array($dataArray)) {
            $dataArray[$key] = $storeData;
        } else {
            $dataArray = array($key => $storeData);
        }
        $cacheData = json_encode($dataArray);

        return file_put_contents($this->getCacheDir(), $cacheData) > 0;
    }

    /**
     * 获取缓存数据
     * @param string $key 缓存key
     * @return object
     */
    public function getData($key) {
        $cachedData = $this->loadCacheInfo();
        $cacheInfo  = $cachedData[$key];
        if (!isset($cacheInfo)) {
            return null;
        }
        if (true === $this->_checkExpired($cacheInfo['time'], $cacheInfo['expire'])) {
            //删除过期的缓存数据
            $this->eraseExpired();

            return null;
        }

        return unserialize($cachedData[$key]['data']);
    }

    /**
     * 缓存是否存在
     * @param string $key 缓存key
     * @return bool
     */
    public function  hasKey($key) {
        if (false != $this->loadCacheInfo()) {
            $cachedData = $this->loadCacheInfo();

            return isset($cachedData[$key]['data']);
        }
    }

    /**
     * 删除某个缓存key
     * @param string $key
     * @return bool
     */
    public function  delKey($key) {
        $cacheData = $this->loadCacheInfo();
        if (true === is_array($cacheData)) {
            if (true === isset($cacheData[$key])) {
                unset($cacheData[$key]);
                $cacheData = json_encode($cacheData);
                file_put_contents($this->getCacheDir(), $cacheData);
            } else {
                throw new Exception("Error: erase() - Key '{$key}' not found.");
            }
        }

        return true;
    }
}