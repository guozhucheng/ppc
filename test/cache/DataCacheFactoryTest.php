<?php
namespace test\cache;
require_once(__DIR__ . '/../../cache/loader.php');

use cache\DataCacheFactory;
use cache\SimpleCache;
use PHPUnit_Framework_TestCase;

/**
 * DataCacheFactory 单元测试
 * Created by guozhucheng@baidu.com
 */
class DataCacheFactoryTest extends PHPUnit_Framework_TestCase {

    /**
     * createCache单元测试
     */
    public function  testCreateCache() {
        $simpleCache = new SimpleCache();
        $cache       = DataCacheFactory::createCache('SimpleCache');

        $this->assertEquals($simpleCache, $cache);
    }
}
 