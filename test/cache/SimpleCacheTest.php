<?php


namespace test\cache;

use cache\SimpleCache;
use PHPUnit_Framework_TestCase;

require_once(__DIR__ . '/../../cache/loader.php');

/**
 * SimpleCache类单元测试
 * Class SimpleCacheTest
 * @package test\cache
 */
class SimpleCacheTest extends PHPUnit_Framework_TestCase {

//    /**
//     * 测试loadCache方法
//     */
//    public function testLoadCache() {
//        $simpleCache      = new SimpleCache();
//        $ref_SimpleCache  = new ReflectionClass(get_class($simpleCache));
//        $method_loadCache = $ref_SimpleCache->getMethod('loadCache');
//        $method_loadCache->setAccessible(true);
//        $resut = $method_loadCache->invoke($simpleCache);
//        var_export($resut);
//    }

    /**
     * test function addData
     */
    public function  testAddData() {
        $mockSimple = $this->getMock('SimpleCache', array('filePutContents', 'loadCacheInfo'));
        $mockSimple->expects($this->any())->method('filePutContents')->will($this->returnValue(1));
        $mockSimple->expects($this->any())->method('loadCacheInfo')->will($this->returnValue(array()));

        $simpleCache = new SimpleCache();
        $ret         = $simpleCache->addData('key', 'data', 10);

        $this->assertEquals($ret, true);
    }

    public function  testGetData() {
        $cacheData = array('key' => array('time' => date(''), 'expire' => 36000, 'data' => 'data'));

        $mockSimple = $this->getMock('SimpleCache');
        $mockSimple->expects($this->any())->method('loadCacheInfo')->will($this->returnValue($cacheData));

        $simpleCache = new SimpleCache();
        $ret         = $simpleCache->getData('key');
        var_export($ret);
        var_export($cacheData['key']['data']);
        $this->assertEquals($ret, $cacheData['key']['data']);

    }
}
 