<?php

/**
 * 类工厂，用于产生类的实例
 * 这里通过工厂+魔术方法的方式实现了简单的AOP框架
 * 在实际的项目中，倾向于使用AOP框架来实现切片式的编程以提高代码的可读性和性能
 * Class ClassFactory
 */
class ClassFactory {

    /**
     * 获取工厂加工后的实例
     * @param class $instance
     * @return AopClass
     */
    public function getInstance($instance) {
        return new AopClass($instance);
    }
}

class AopClass {
    private $_instance;

    public function __construct($instance) {
        $this->_instance = $instance;
    }

    public function __call($method, $arguments) {
        if (!method_exists($this->_instance, $method)) {
            throw new Exception($method . '方法未定义');
        }
        //执行参数检查
        ParamFilter::paramsCheck(get_class($this->_instance), $method, $arguments);
        $realMethod = array($this->_instance, $method);

        return call_user_func($realMethod, $arguments);
    }
}