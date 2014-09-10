<?php
namespace test\cache;
require_once('../../cache/SimpleCache.php');
require_once('../../cache/IDataCache.php');
require_once('../../cache/DataCacheFactory.php');


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
 