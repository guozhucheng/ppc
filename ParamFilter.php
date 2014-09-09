<?php

use cache\DataCacheFactory;
use cache\IDataCache;
use paramCheckResult\IParamCheckResult;
use paramCheckResult\ParamCheckResultFactory;

//require_once('TestClass.php');
require_once(__DIR__ . '/ParamDocInfo.php');
require_once(__DIR__ . '/cache/IDataCache.php');
require_once(__DIR__ . '/cache/DataCacheFactory.php');
require_once(__DIR__ . '/paramCheckResult/IParamCheckResult.php');
require_once(__DIR__ . '/paramCheckResult/ParamCheckResultFactory.php');

/**
 * 参数过滤器
 * Created by guozhucheng@baidu.com
 */
class  ParamFilter {
    //缓存key基础前缀
    const   CACHE_BASE_NAME = 'PARAMFILLTER_CACHE';
    //默认缓存名称
    const   DEFAULT_CACHE = 'SimpleCache';
    //默认参数结果实现类
    const  COMMON_RESULT   = 'CommonParamParamCheckResult';
    const   CACHE_DURATION = 300; //默认缓存5分钟
    //缓存对象
    private static $_cache;

    //检查结果对象
    private static $_checkResult;

    /**
     * 获取缓存对象
     * @return IDataCache
     */
    private static function  getCache() {
        if (self::$_cache == null) {
            self::$_cache = DataCacheFactory::createCache(self::DEFAULT_CACHE, self::CACHE_BASE_NAME);
            //todo temp
            self::$_cache = null;
        }

        return self::$_cache;
    }

    /**
     * 获取参数结果对象
     * @return IParamCheckResult
     */
    private static function getCheckResult() {
        if (self::$_checkResult == null) {
            self::$_checkResult = ParamCheckResultFactory::createReuslt(self::COMMON_RESULT);
        }

        return self::$_checkResult;
    }

    /**
     * 根据分割后的函数注释获取ParamDocInfo对象数组
     * @param array $docs
     * @return array
     */
    private static function docsToParamDocInfos(array $docs) {
        $parmDocInfos = array();
        foreach ($docs as $doc) {
            array_push($parmDocInfos, new ParamDocInfo($doc));
        }

        return $parmDocInfos;
    }


    /**
     * 根据注释的完整信息 解析出每条参数的注释内容
     * @param string $documents 函数注释内容
     * @return array
     */
    private function  getDocs($documents) {
        $outParams   = null;
        $paramStrArr = array();
        //解析出函数注释中参数描述的部分
        preg_match_all("/\\@param([\\s\\S]*?)\\*/", $documents, $outParams);
        if (is_array($outParams)) {
            foreach ($outParams[1] as $paramStr) {
                //获取注释中关于参数描述的部分
                array_push($paramStrArr, trim(trim(trim($paramStr, '@param'), '*')));
            }
        }

        return $paramStrArr;
    }

    /**
     * 参数校验
     * @param AopJoinPoint $object
     * @return array
     */
    public static function  paramsCheck(AopJoinPoint $object = null) {

        //region test
        $arguments = array(1, 'str', '2014-9-9');
        //获取类名称
        $className = 'TestClass';
        //获取方法名称
        $fucName = 'testFuc1';

        require_once('TestClass.php');
        //endregion


//        //region  通过php-aop扩展（详见https://github.com/AOP-PHP/AOP）获取运行时的函数信息
//        //获取实参
//        $arguments = $object->getArguments();
//        //获取类名称
//        $className = $object->getClassName();
//        //获取方法名称
//        $fucName = $object->getMethodName();
//
//        // endregion

        //反射获取函数注释部分 并对注释进行分割
        $clsInstance = new ReflectionClass($className);
        $fucIns      = $clsInstance->getMethod($fucName);
        $doc         = $fucIns->getDocComment();


        $paramDocs     = self::getDocs($doc);
        $paramDocInfos = self:: docsToParamDocInfos($paramDocs);
        //获取函数名称
        $paraNames = array();
        foreach ($fucIns->getParameters() as $param) {
            array_push($paraNames, $param->getName());
        }

        $paramInfos = array();
        //查询缓存中是否有反射结果
        $cache    = self::getCache();
        $cacheKey = 'REFLECTIONCACHE_' . $className . '_' . $fucName;
        if ($cache != null && $cache->hasKey($cacheKey)) {
            $paramInfos = $cache->getData($cacheKey);
        } else { //缓存中没有反射结果，则进行反射

            //生成ParmDocInfo对象数组
            foreach ($paraNames as $paramName) {
                $paramDocInfo = null;

                foreach ($paramDocInfos as $paramDoc) {
                    if (trim($paramDoc->getName(), '$') == $paramName) {
                        $paramDocInfo = $paramDoc;
                        break;
                    }
                }
                array_push($paramInfos, array('name' => $paramName, 'paramdocinfo' => $paramDocInfo));
            }
            //将反射结果存入缓存中
            if ($cache != null) {
                $cache->addData($cacheKey, $paramInfos, self::CACHE_DURATION);
            }
        }


        $count = count($arguments);
        for ($i = 0; $i < $count; $i++) {
            $paramDocInfo = $paramInfos[$i]['paramdocinfo'];
            if (!$paramDocInfo->isLegal($arguments[$i])) {
                $checkResult = self::getCheckResult();
                var_export($checkResult);
                $checkResult->setCheckResult($paramInfos[$i]['name'], '参数类型校验与注释说明不匹配');
            }
        }
    }

}

