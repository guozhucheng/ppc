<?php
/**
 * Created by guozhucheng@baidu.com
 * DateTime: 14-9-9 上午1:22
 */

namespace paramCheckResult;


abstract class ParamCheckResultFactory {
    const  COMMONRESULT = 'CommonParamParamCheckResult';

    static function  createReuslt($resultName) {
        //目前仅有一种CommonParamParamCheckResult一种实现
        switch ($resultName) {
            case self::COMMONRESULT:
                return new CommonParamParamCheckResult();
                break;
            default:
                return new CommonParamParamCheckResult();
                break;
        }
    }
} 