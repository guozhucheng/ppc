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
     * 待验证函数示例
     * @param int    $p1
     * @param uint   $p2
     * @param float  $p3
     * @param bool   $p4
     * @param        string notnull $p5
     * @param  array $p6
     * @param  date  $p7
     */
    public function  demoMethod($p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8) {
        echo '<br> invoke method';
    }
}

$ins = ClassFactory::getInstance(new Demo());

//验证通过
$ins->demoMethod(-10, 2, 3.14, true, 'str', array(), '2014-9-9 12:12:122');

//p2 is illegal
$ins->demoMethod(-10, -2, 3.14, true, 'str', array(), '2014-9-9 12:12:122');

//p5 is illegal
$ins->demoMethod(-10, 2, 3.14, true, null, array(), '2014-9-9 12:12:122');

//上述代码将会输出
//invoke method
//param p2 is illegal,because 参数类型校验与注释说明不匹配
//param p5 is illegal,because 参数类型校验与注释说明不匹配