<?php


namespace test\cache;

use cache\SimpleCache;

require_once(__DIR__ . '/../../cache/loader.php');

/**
 * SimpleCache类单元测试
 * Class SimpleCacheTest
 * @package test\cache
 */
class SimpleCacheTest extends PHPUnit_Framework_TestCase {

    /**
     * test function addData
     */
    function  testAddData() {
        $mockSimple = $this->getMock('SimpleCache', array('filePutContents', 'loadCacheInfo'));
        $mockSimple->expects($this->any())->method('filePutContents')->will($this->returnValue(1));
        $mockSimple->expects($this->any())->method('loadCacheInfo')->will($this->returnValue(array()));

        $simpleCache = new SimpleCache();
        $ret         = $simpleCache->addData('key', 'data', 10);

        $this->assertEquals($ret, true);
    }

    function  testGetData() {
        $cacheData = array('key' => array('time' => date(''), 'expire' => 36000, 'data' => 'data'));

        $mockSimple = $this->getMock('SimpleCache');
        $mockSimple->expects($this->any())->method('loadCacheInfo')->will($this->returnValue($cacheData));

        $simpleCache = new SimpleCache();
        $ret         = $simpleCache->getData('key');

        $this->assertEquals($ret, $cacheData['key']['data']);

    }

    function  testHasKey() {
        $cacheData  = array('key' => array('time' => date(''), 'expire' => 36000, 'data' => 'data'));
        $mockSimple = $this->getMock('SimpleCache');
        $mockSimple->expects($this->any())->method('loadCacheInfo')->will($this->returnValue($cacheData));

        $simple = new SimpleCache();
        $ret    = $simple->hasKey('key');
        $this->assertEquals($ret, true);
        $ret = $simple->hasKey('unexistskey');
        $this->assertEquals($ret, false);

    }

    function  testDelKey() {
        $cacheData  = array('key' => array('time' => date(''), 'expire' => 36000, 'data' => 'data'));
        $mockSimple = $this->getMock('SimpleCache');
        $mockSimple->expects($this->any())->method('loadCacheInfo')->will($this->returnValue($cacheData));
        $mockSimple->expects($this->any())->method('filePutContents')->will($this->returnValue(true));

        $simple = new SimpleCache();
        $ret    = $simple->delKey('key');
        $this->assertEquals($ret, true);
        $ret = $simple->delKey('unexistskey');
        $this->assertEquals($ret, false);

    }

}
