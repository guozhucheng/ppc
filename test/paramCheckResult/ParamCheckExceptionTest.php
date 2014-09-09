<?php

namespace test\paramCheckResult;

use paramCheckResult\ParamCheckException;
use PHPUnit_Framework_TestCase;

require_once(__DIR__ . '/../../paramCheckResult/ParamCheckException.php');

/**
 * ParamCheckException 异常类单元测试
 * Class ParamCheckExceptionTest
 * @package test\paramCheckResult
 */
class ParamCheckExceptionTest extends PHPUnit_Framework_TestCase {
    /**
     * 测试函数 getName
     */
    public function  testGetName() {
        $paramCheckException = new ParamCheckException('paramName', 'paramMessage');
        $ret                 = $paramCheckException->getName();
        $this->assertEquals($ret, 'paramName');
    }
}
 