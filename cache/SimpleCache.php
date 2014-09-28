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
     * 构造函数
     * @param null|array $config
     */
    public function __construct($config = null) {
        if (isset($config)) {
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
     * 添加缓存数据
     * @param string $key 缓存key
     * @param object $data 缓存数据
     * @param int    $duration 缓存时间（秒）
     * @return bool true 添加成功，false 添加失败
     */
    public function  addData($key, $data, $duration) {
        $storeData = array(
            'time' => time(), 'expire' => $duration, 'data' => serialize($data),
        );
        $dataArray = $this->loadCacheInfo();
        if (true === is_array($dataArray)) {
            $dataArray[$key] = $storeData;
        } else {
            $dataArray = array($key => $storeData);
        }
        $cacheData = json_encode($dataArray);

        return self::filePutContents($this->getCacheDir(), $cacheData) > 0;
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
        if (true === $this->checkExpired($cacheInfo['time'], $cacheInfo['expire'])) {
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
        if (is_array($cacheData)) {
            if (true === isset($cacheData[$key])) {
                unset($cacheData[$key]);
                $cacheData = json_encode($cacheData);
                self::filePutContents($this->getCacheDir(), $cacheData);
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * 删除所有的缓存
     * @return $this
     */
    public function delAll() {
        $cacheDir = $this->getCacheDir();
        if (true === self:: fileExists($cacheDir)) {
            self::unlink($cacheDir);
        }

        return $this;
    }

    /**
     * 加载缓存,并处理过期数据
     * @return mixed
     */
    private function loadCacheInfo() {
        self::eraseExpired();

        return self::loadCacheFile();
    }

    /**
     * 加载缓存
     * @return bool|mixed
     */
    private function  loadCacheFile() {
        if (true === self:: fileExists($this->getCacheDir())) {
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
        if (true === $this->checkCacheDir()) {
            $filename = $this->_cachename;
            $filename = preg_replace('/[^0-9a-z\.\_\-]/i', '', strtolower($filename));

            return $this->_cachePath . $this->getHash($filename) . $this->_extension;
        }
    }


    /**
     * 移除过期的缓存
     * @return int
     */
    private function eraseExpired() {
        $cacheData = $this->loadCacheFile();
        if (true === is_array($cacheData)) {
            $counter = 0;
            foreach ($cacheData as $key => $entry) {
                if (true === $this->checkExpired($entry['time'], $entry['expire'])) {
                    unset($cacheData[$key]);
                    $counter++;
                }
            }
            if ($counter > 0) {
                $cacheData = json_encode($cacheData);
                self::filePutContents($this->getCacheDir(), $cacheData);
            }

            return $counter;
        }
    }


    /**
     * 获取缓存名称hash
     * @param $filename
     * @return string
     */
    private function getHash($filename) {
        return sha1($filename);
    }

    /**
     * 检查是否过期
     * @param int $timestamp 时间戳
     * @param int $expiration 过期时间（秒）
     * @return bool
     */
    private function checkExpired($timestamp, $expiration) {
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
    private function checkCacheDir() {
        if (!self::isDir($this->_cachePath) && !self::mkDir($this->_cachePath, 0775, true)) {
            throw new Exception('无法创建缓存目录' . $this->_cachePath);
        } elseif (!self::isReadable($this->_cachePath) || !self::isWritable($this->_cachePath)) {
            if (!chmod($this->_cachePath, 0775)) {
                throw new Exception($this->_cachePath . ' 目录必须可读可写');
            }
        }

        return true;
    }

    /**
     * 将数据写入文件中
     * @param string $filename 文件名称
     * @param mixed  $data 写入的数据
     * @return int 返回写入的字节数
     */
    private function  filePutContents($filename, $data) {
        return file_put_contents($filename, $data);
    }

    /**
     * 判断文件是否存在
     * @param string $filename 文件名称
     * @return bool
     */
    private function isDir($filename) {
        return is_dir($filename);
    }

    /**
     * 生成文件目录
     * @param string $pathname 文件名称
     * @param int    $mode
     * @param bool   $recursive
     * @return bool
     */
    function mkDir($pathname, $mode = 0777) {
        return mkdir($pathname, $mode);
    }

    /**
     * 文件是否存在
     * @param string $filename 文件名称
     * @return bool
     */
    function fileExists($filename) {
        return file_exists($filename);
    }

    /**
     * 判读文件存在及可读
     * @param string $filename 文件名称
     * @return bool
     */
    function isReadable($filename) {
        return is_readable($filename);
    }

    /**
     * 判断文件是否可写
     * @param string $filename 文件名称
     * @return bool
     */
    function isWritable($filename) {
        return is_writable($filename);
    }

    /**
     * 删除文件
     * @param $filename 文件名
     * @return bool
     */
    function unLink($filename) {
        return unlink($filename);
    }

}