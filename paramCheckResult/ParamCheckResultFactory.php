<?php
namespace paramCheckResult;
require_once(__DIR__ . '/../paramCheckResult/IParamCheckResult.php');
require_once(__DIR__ . '/../paramCheckResult/CommonParamCheckResult.php');

/**
 * 参数检查结果抽象工厂类
 * Class ParamCheckResultFactory
 * @package paramCheckResult
 */
abstract class ParamCheckResultFactory {
    const  COMMONRESULT = 'CommonParamParamCheckResult';

    /**
     * 获取检查结果对象实例
     * @param $resultName
     * @return IParamCheckResult
     */
    static function  createReuslt($resultName) {
        //目前仅有一种CommonParamParamCheckResult一种实现
        switch ($resultName) {
            case self::COMMONRESULT:
                return new CommonParamCheckResult();
                break;
            default:
                return new CommonParamCheckResult();
                break;
        }
    }
} 