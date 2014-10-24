<?php

use cache\DataCacheFactory;
use cache\IDataCache;
use paramCheckResult\IParamCheckResult;
use paramCheckResult\ParamCheckResultFactory;
use paramCheckResult\ParamIllegalException;

require_once(__DIR__ . '/ParamDocInfo.php');
require_once(__DIR__ . '/cache/loader.php');
require_once(__DIR__ . '/paramCheckResult/loader.php');

/**
 * 参数过滤器
 * Created by guozhucheng@baidu.com
 */
class  ParamFilter {
    //缓存key基础前缀
    const CACHE_BASE_NAME = 'PARAMFILLTER_CACHE';
    //默认缓存名称
    const DEFAULT_CACHE = 'SimpleCache';
    //默认参数结果实现类
    const COMMON_RESULT = 'CommonParamParamCheckResult';
    //默认缓存5分钟
    const CACHE_DURATION = 300;
    // 反射的缓存key定义
    const REFLECT_CACHE_KEY = "REFLECTION_CACHE_";

    //缓存对象
    private static $_cache;
    //检查结果对象
    private static $_checkResult;

    /**
     * 执行参数检查
     * @param string $className 类名称
     * @param string $method 函数名
     * @param array  $arguments 实参数组
     * @throws ParamIllegalException 如果参数不合法则抛出异常
     */
    public static function paramsCheck($className, $method, $arguments) {

        $paramInfos = array();

        //查询缓存中是否有反射结果
        $cache    = self::getCache();
        $cacheKey = self::REFLECT_CACHE_KEY . $className . '_' . $method;

        //缓存中没有数据，则进行反射，并将反射的结果加入缓存中
        if ($cache == null || !$cache->hasKey($cacheKey)) {
            //反射获取函数注释部分 并对注释进行分割
            $clsInstance = new ReflectionClass($className);
            $fucIns      = $clsInstance->getMethod($method);
            $doc         = $fucIns->getDocComment();
            $paramDocs   = self::getDocs($doc);

            $paramDocInfos = self::docsToParamDocInfos($paramDocs);
            //获取函数名称
            $paraNames = array();
            foreach ($fucIns->getParameters() as $param) {
                array_push($paraNames, $param->getName());
            }
            //生成ParamDocInfo对象数组
            foreach ($paraNames as $paramName) {
                $paramDocInfo = null;
                foreach ($paramDocInfos as $paramDoc) {
                    if ($paramDoc->getName() == $paramName) {
                        $paramDocInfo = $paramDoc;
                        break;
                    }
                }
                array_push($paramInfos, array(
                    'name'         => $paramName,
                    'paramdocinfo' => $paramDocInfo,
                ));
            }
            //将反射结果存入缓存中
            if ($cache != null) {
                $cache->addData($cacheKey, $paramInfos, self::CACHE_DURATION);
            }
        } else { //缓存中没有反射结果，则进行反射
            $paramInfos = $cache->getData($cacheKey);
        }
        //遍历实参
        foreach ($arguments as $key => $paramVal) {
            $paramDocInfo = $paramInfos[$key]['paramdocinfo'];
            if (isset($paramDocInfo) && !$paramDocInfo->isLegal($paramVal)) {
                throw new ParamIllegalException($paramInfos[$key]['name'], 'Expect Type:' . $paramDocInfo->getType());
            }
        }


    }

    /**
     * 获取缓存对象
     * @return IDataCache
     */
    private static function getCache() {
        if (self::$_cache == null) {
            self::$_cache = DataCacheFactory::createCache(self::DEFAULT_CACHE, self::CACHE_BASE_NAME);
        }

        return self::$_cache;
    }

    /**
     * 获取参数结果对象
     * @return IParamCheckResult
     */
    private static function getCheckResult() {
        if (self::$_checkResult == null) {
            self::$_checkResult = ParamCheckResultFactory::createResult(self::COMMON_RESULT);
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
    private function getDocs($documents) {
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
}
