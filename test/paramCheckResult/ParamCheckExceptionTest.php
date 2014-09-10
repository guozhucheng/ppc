<?php

namespace test\paramCheckResult;

use paramCheckResult\ParamIllegalException;
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
        $paramCheckException = new ParamIllegalException('paramName', 'paramMessage');
        $ret                 = $paramCheckException->getName();
        $this->assertEquals($ret, 'paramName');
    }
}
 