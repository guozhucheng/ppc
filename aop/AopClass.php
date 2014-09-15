<?php

namespace aop;
require_once(__DIR__ . '/../paramCheckResult/loader.php');
use Exception;
use paramCheckResult\ParamCheckResultFactory;
use paramCheckResult\ParamIllegalException;
use ParamFilter;

/**
 * 简单的aop实现
 * 对需要进行切面参数校验的类用此类进行分装
 * 封装后的类进行方法调用时会触发魔术方法__call的调用,在执行函数体之前,进行参数校验
 * Class AopClass
 */
class AopClass {
    //通用检查结果类名称
    const  COMMON_RESULT_CLASS = 'CommonParamParamCheckResult';
    private $_instance;


    /**
     * 构造函数
     * @param $instance 需要进行aop封装类的实例
     */
    public function __construct($instance) {
        $this->_instance = $instance;
    }

    /**
     * 魔术方法 用于在函数执行前执行参数检查
     * @param string $method 方法名称
     * @param array  $arguments 实参数组
     * @return mixed
     * @throws \Exception 验证不通过是
     */
    public function __call($method, $arguments) {
        if (!method_exists($this->_instance, $method)) {
            throw new Exception($method . '方法未定义');
        }
        try { //执行参数检查
            ParamFilter::paramsCheck(get_class($this->_instance), $method, $arguments);
        } catch (ParamIllegalException $e) { //捕获到异常，参数校验失败
            $checkResult = ParamCheckResultFactory::createReuslt(self::COMMON_RESULT_CLASS);

            return $checkResult->setCheckResult($e->getName(), $e->getMessage());
        }

        //通过参数校验,进行函数调用
        return call_user_func_array(array($this->_instance, $method), $arguments);
    }
}