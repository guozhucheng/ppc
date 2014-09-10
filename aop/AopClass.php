<?php

namespace aop;

use Exception;
use ParamFilter;

/**
 * 简单的aop实现
 * Class AopClass
 * @package aop
 */
class AopClass {
    private $_instance;

    /**
     * 构造函数
     * @param $instance
     */
    public function __construct($instance) {
        $this->_instance = $instance;
    }

    public function __call($method, $arguments) {
        if (!method_exists($this->_instance, $method)) {
            throw new Exception($method . '方法未定义');
        }
        //执行参数检查
        $className = get_class($this->_instance);
        ParamFilter::paramsCheck($className, $method, $arguments);

        return call_user_func_array(array($this->_instance, $method), $arguments);
    }
}