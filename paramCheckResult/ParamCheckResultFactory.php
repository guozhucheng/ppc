<?php
namespace paramCheckResult;

use SebastianBergmann\Exporter\Exception;


/**
 * 参数检查结果抽象工厂类
 * Class ParamCheckResultFactory
 */
abstract class ParamCheckResultFactory {

    //通用检查结果类名称
    const  COMMON_RESULT_CLASS = 'CommonParamParamCheckResult';

    /**
     * 获取检查结果对象实例
     * @param $resultName
     * @return IParamCheckResult
     */
    static function  createResult($resultName) {
        //目前仅有一种CommonParamParamCheckResult一种实现
        switch ($resultName) {
            case self::COMMON_RESULT_CLASS:
                return new CommonParamCheckResult();
            default:
                throw new Exception($resultName . '结果类未定义');
        }
    }
} 