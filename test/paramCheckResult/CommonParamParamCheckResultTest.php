<?php

namespace test\paramCheckResult;

use paramCheckResult\CommonParamCheckResult;
use paramCheckResult\ParamCheckException;

require_once('../../paramCheckResult/IParamCheckReuslt.php');
require_once('../../paramCheckResult/CommonParamCheckResult.php');
require_once('../../paramCheckResult/ParamCheckException.php');


class CommonParamParamCheckResultTest extends \PHPUnit_Framework_TestCase {
    public function  testSetCheckResult() {
        try {
            $object = new CommonParamCheckResult();
             $object->setCheckResult('paramName', 'reason');

        } catch (ParamCheckException $exception) {
            $res = new ParamCheckException('paramName', 'reason');
            $this->assertEquals($res, $exception);
            return;
        }
        $this->fail();


    }
}
 