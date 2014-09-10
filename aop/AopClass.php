<?php

namespace aop;
require_once(__DIR__ . '/../paramCheckResult/loader.php');
use Exception;
use paramCheckResult\ParamCheckResultFactory;
use paramCheckResult\ParamIllegalException;
use ParamFilter;

/**
 * 简单的aop实现
 * Class AopClass
 * @package aop
 */
class AopClass {
    //通用检查结果类名称
    const  COMMONRESULT = 'CommonParamParamCheckResult';
    private $_instance;


    /**
     * 构造函数
     * @param $instance
     */
    public function __construct($instance) {
        $this->_instance = $instance;
    }

    /**
     * 魔术方法 用于在函数执行前执行参数检查
     * @param string $method 方法名称
     * @param array  $arguments 实参数组
     * @return mixed
     * @throws \Exception
     */
    public function __call($method, $arguments) {
        if (!method_exists($this->_instance, $method)) {
            throw new Exception($method . '方法未定义');
        }
        try { //执行参数检查
            ParamFilter::paramsCheck(get_class($this->_instance), $method, $arguments);
        } catch (ParamIllegalException $e) { //捕获到异常，参数校验失败
            $checkResult = ParamCheckResultFactory::createReuslt(self::COMMONRESULT);

            return $checkResult->setCheckResult($e->getName(), $e->getMessage());
        }

        return call_user_func_array(array($this->_instance, $method), $arguments);
    }
}