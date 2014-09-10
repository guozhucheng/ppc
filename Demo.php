<?php


use aop\ClassFactory;

require_once('ParamFilter.php');
require_once(__DIR__ . '/paramCheckResult/loader.php');
require_once(__DIR__ . '/cache/loader.php');
require_once(__DIR__ . '/aop/loader.php');

/**
 * 演示类
 * Class Demo
 */
class Demo {

    /**
     * 被验证方法
     * @param int  $p1 第一个参数，用于验证整数(int)
     * @param      string notnull $p2 第二个参数，用于验证string同时不为空
     * @param date $p3 date参数类型
     * @return  array
     */
    public function  method1($p1, $p2, $p3) {
        echo '参数验证通过';
    }
}

$ins = ClassFactory::getInstance(new Demo());
$ins->method1('1a', 'str', '2014-9-9 12:12:12');

