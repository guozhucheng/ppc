<?php

namespace paramCheckResult;
require_once(__DIR__ . '/../paramCheckResult/IParamCheckResult.php');
require_once(__DIR__ . '/../paramCheckResult/ParamCheckException.php');

/**
 * 通用参数检查结果
 * Class CommonParamCheckResult
 * @package paramCheckResult
 */
class CommonParamCheckResult implements IParamCheckResult {

    /**
     * 设置参数检查结果
     * @param   string $paramName 参数名称
     * @param   object $reason 原因
     * @return mixed
     */
    public function setCheckResult($paramName, $reason) {
        $exception = new ParamCheckException($paramName, $reason);
        throw $exception;
    }
}