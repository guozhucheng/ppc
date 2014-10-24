<?php
/**
 * 参数检查结果接口定义，需要实现自定义的参数检查结果，可实现该接口，并在工厂类ParamCheckResultFactory中实现
 */
namespace paramCheckResult;
/**
 * 参数检查结果结果
 * 不同的项目可通过实现该接口,实现自定义的结果定义
 * Interface ICheckReuslt
 */
interface IParamCheckResult {

    /**
     * 参数囧啊眼失败
     * @param   string $paramName 参数名称
     * @param   object $reason 原因
     * @return mixed
     */
    public function checkFailed($paramName, $reason);
}