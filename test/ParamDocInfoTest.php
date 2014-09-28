<?php
/**
 * Created by guozhucheng@baidu.com
 * DateTime: 14-9-24 下午2:43
 */
namespace test;
require_once(__DIR__ . '/../ParamDocInfo.php');

class ParamDocInfoTest extends \PHPUnit_Framework_TestCase {
    function  testIsInt() {

        $isIntMethod = new \ReflectionMethod('ParamDocInfo', 'isInt');
        $isIntMethod->setAccessible(true);
        $ret = $isIntMethod->invoke(null, '123');
        $this->assertEquals($ret, true);

        $ret = $isIntMethod->invoke(null, '-1234');
        $this->assertEquals($ret, true);

        $ret = $isIntMethod->invoke(null, '0123');
        $this->assertEquals($ret, true);

        $ret = $isIntMethod->invoke(null, '0129');
        $this->assertEquals($ret, false);

        $ret = $isIntMethod->invoke(null, '0x1A');
        $this->assertEquals($ret, true);

        $ret = $isIntMethod->invoke(null, '0x0a');
        $this->assertEquals($ret, false);

    }
}
 