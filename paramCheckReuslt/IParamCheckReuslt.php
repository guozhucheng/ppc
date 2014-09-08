<?php
/**
 * 参数检查结果接口定义，需要实现自定义的参数检查结果，可实现该接口，并在工厂类ParamCheckResultFactory中实现
 * Created by guozhucheng@baidu.com
 */
namespace paramCheckResult;
/**
 * 参数检查结果结果
 * Interface ICheckReuslt
 */
interface IParamCheckReuslt {

    /**
     * 设置参数检查结果
     * @param   string $paramName 参数名称
     * @param   object $reason 原因
     * @return mixed
     */
    public function setCheckResult($paramName, $reason);
}