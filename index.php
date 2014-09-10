<?php

/**
 * Created by guozhucheng@baidu.com
 * DateTime: 14-8-31 上午1:58
 */
class TestClass {

    /** 被测函数
     * @param int  $p1
     * @param      string notnull $p2
     * @param date $p3
     * @return  array
     */
    public function  testFuc1($p1, $p2, $p3) {
        var_export(func_get_args());
        //  return array('testres' => 'test');
    }
}


use paramCheckResult\ParamCheckException;
use aop\ClassFactory;

require_once('ParamFilter.php');
require_once(__DIR__ . '/paramCheckResult/loader.php');
require_once(__DIR__ . '/cache/loader.php');
require_once(__DIR__ . '/aop/loader.php');

$testIns = ClassFactory::getInstance(new TestClass());
try {
    $testIns->testFuc1('1a', 'str', '2014-9-9 12:12:12');
} catch (ParamCheckException $e) {
    var_export($e->getMessage());
}