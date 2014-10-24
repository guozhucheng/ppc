<?php
namespace cache;

/**
 * 缓存项,类在创建时尝试按照实例信息对应的文件目录加载一次缓存信息至内存中;
 * 对象销毁时,将内存中的数据再写回到文件中;
 * 对于过期缓存的处理,
 * Class CacheItem
 * @package cache
 */
class CacheItem {

    /**
     * 清理过期缓存后,缓存的最大数量,超过这个数量的缓存将会被移除(优先移除快到期的缓存)
     */
    const MAX_CACHE_COUNT = 10000;


    /**
     * 为了防止频繁的移除过期缓存造成的开销,当缓存数量增加超过RELEASE_CACHE_COUNT时,才清理一次过期缓存
     */
    const  RELEASE_CACHE_COUNT = 100;

    /**
     * 期间增加的缓存数量
     * @var int
     */
    private $duringIncreCacheCount = 0;
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
     * 数据缓存对象
     * @var
     */
    private $cachInfo = array();


    /**
     *构造缓存对象
     * @param null|array $config
     */
    public function  __construct($config = null) {
        if (is_string($config)) {
            $this->_cachename = $config;
        } else if (is_array($config)) {
            $this->_cachename = isset($config['name']) ? $config['name'] : $this->_cachename;
            $this->_cachePath = isset($config['path']) ? $config['path'] : $this->_cachePath;
            $this->_extension = isset($config['extension']) ? $config['extension'] : $this->_extension;
        }
        //对象构造时,从文件中一次性读入缓存,
        $cacheInfo = $this->loadCacheInfo();
        if ($cacheInfo !== false) {
            $this->cachInfo = $cacheInfo;

        }
    }

    /**
     * 当缓存对象销毁时,将缓存由内存写入文件
     */
    public function __destruct() {
        file_put_contents($this->getCacheDir(), json_encode($this->cachInfo));
    }


    /**
     * 获取数据项
     * @param stirng $key
     * @return bool|array
     */
    public function getCachInfo($key) {
        if (!array_key_exists($key, $this->cachInfo)) {
            return false;
        }

        return $this->cachInfo[$key]['data'];
    }

    /**
     * 存入缓存文件
     * @param $key
     * @param $data
     * @param $duration
     * @return bool
     */
    public function  addCache($key, $data, $duration) {
        //当期间增加的缓存数量超过配置值时,进行一次移除过期缓存的操作,并置0计数器
        if ($this->duringIncreCacheCount >= self::RELEASE_CACHE_COUNT) {
            $this->removeExpireData();
            $this->duringIncreCacheCount = 0;
        }
        $this->cachInfo[$key] = array(
            'data'       => $data,
            'expiretime' => time() + $duration,
        );
        $this->duringIncreCacheCount++;

        return array_key_exists($key, $this->cachInfo);
    }

    /**
     * 缓存key 是否存在
     * @param string $key
     * @return bool true 存在,false不存在
     */
    public function  hasKey($key) {
        if (array_key_exists($key, $this->cachInfo)) {
            if (!$this->checkExpired($this->cachInfo[$key]['expiretime'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * 移除key
     * @param $key
     * @return bool true 移除成功,false 移除的key不存在
     */
    public function  removeKey($key) {
        if ($this->hasKey($key)) {
            unset($this->cachInfo[$key]);
            $this->duringIncreCacheCount--;

            return true;
        }

        return false;
    }

    /**
     * usort使用的排序函数,按照expiretime倒序排序
     * @param array $cacheA 比较元素A
     * @param array $cacheB 比较元素B
     * @return int 0相等 1:$cacheA['expiretime']<$cacheB['expiretime']
     * -1:$cacheA['expiretime']>$cacheB['expiretime']
     */
    public static function  sortCacheInfo($cacheA, $cacheB) {
        if ($cacheA['expiretime'] == $cacheB['expiretime']) {
            return 0;
        }

        return $cacheA['expiretime'] < $cacheB['expiretime'] ? 1 : -1;
    }

    /**
     * 移除过期的数据
     */
    private function removeExpireData() {
        //先按照过期时间进行倒排
        usort($this->cachInfo, array(
            __CLASS__,
            'sortCacheInfo',
        ));
        $cacheCount = count($this->cachInfo);
        while ($cacheCount > 0) {
            $item = array_pop($this->cachInfo);
            //缓存未过期并且缓存数量未超过总量规定
            if (!$this->checkExpired($item['expiretime']) && count($this->cachInfo < self::MAX_CACHE_COUNT)) {
                array_push($this->cachInfo, $item); //重新将数据推入数组
                break;
            }
            $cacheCount = count($this->cachInfo);
        }
    }

    /**
     * 检查是否过期
     * @param int $timestamp 过期时间戳
     * @return bool true过期,false未过期
     */
    private function checkExpired($expiration) {
        $timeLeft = $expiration - time();

        return $timeLeft < 0;
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
     * 检查缓存中的目录是否可写，如果目录不存在就创建一个
     * @return bool
     * @throws Exception
     */
    private function checkCacheDir() {
        if (!is_dir($this->_cachePath) && !mkdir($this->_cachePath, 0775, true)) {
            throw new Exception('无法创建缓存目录' . $this->_cachePath);
        }
        if (!is_readable($this->_cachePath) || !is_readable($this->_cachePath)) {
            if (!chmod($this->_cachePath, 0775)) {
                throw new Exception($this->_cachePath . ' 目录必须可读可写');
            }
        }

        return true;
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
     * 加载缓存
     * @return bool|mixed
     */
    private function  loadCacheInfo() {
        if (true === file_exists($this->getCacheDir())) {
            $file = file_get_contents($this->getCacheDir());

            return json_decode($file, true);
        } else {
            return false;
        }
    }
}