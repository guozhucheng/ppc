<?php

namespace test\paramCheckResult;

use paramCheckResult\ParamCheckException;

require_once('../../paramCheckResult/ParamCheckException.php');


class ParamCheckExceptionTest extends \PHPUnit_Framework_TestCase {
    public function  testGetName() {
        $paramCheckException = new ParamCheckException('paramName', 'paramMessage');
        $ret                 = $paramCheckException->getName();
        var_export($ret);
        $this->assertEquals($ret, 'paramName');
    }
}
 