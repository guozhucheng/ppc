<?php
namespace aop;
/**
 * 类工厂，用于产生类的实例
 * 这里通过工厂+魔术方法的方式实现了简单的AOP框架
 * 在实际的项目中，倾向于使用AOP框架来实现切片式的编程以提高代码的可读性和性能
 * Class ClassFactory
 */
class ClassFactory {

    /**
     * 获取工厂加工后的实例
     * @param object(typeof class) $instance
     * @return \aop\AopClass
     */
    public static function getInstance($instance) {
        return new AopClass($instance);
    }
}

