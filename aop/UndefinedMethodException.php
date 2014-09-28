<?php

/**
 * 执行未定义的method时抛出的异常
 */
class UndefinedMethodException extends Exception {

    /**
     * 异常类构造函数
     * @param string $method  method 名称
     */
    public function __construct($method) {
        $this->_paramName = $method;
        parent:: __construct($method . '未定义');
    }
} 