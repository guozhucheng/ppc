<?php


namespace test\cache;

use cache\SimpleCache;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

require_once('../../cache/SimpleCache.php');
require_once('../../cache/IDataCache.php');
require_once('../../cache/DataCacheFactory.php');

/**
 * SimpleCache类单元测试
 * Class SimpleCacheTest
 * @package test\cache
 */
class SimpleCacheTest extends PHPUnit_Framework_TestCase {

    /**
     * 测试loadCache方法
     */
    public function testLoadCache() {
        $simpleCache      = new SimpleCache();
        $ref_SimpleCache  = new ReflectionClass(get_class($simpleCache));
        $method_loadCache = $ref_SimpleCache->getMethod('loadCache');
        $method_loadCache->setAccessible(true);

        $resut = $method_loadCache->invoke($simpleCache);

        var_export($resut);


    }
}
 