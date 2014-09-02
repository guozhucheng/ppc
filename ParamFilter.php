<?php

require_once('TestClass.php');

/**
 * 参数过滤器
 * Created by guozhucheng@baidu.com
 * DateTime: 14-8-31 上午1:50
 */
class ParamFilter {
    /**
     * 根据注释的完整信息 解析出每条参数的注释内容
     * @param $documents 函数注释内容
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
     * 解析函数的注释内容
     * @param $className
     * @param $fucName
     */
    public function resolveDoc($className, $fucName) {
        //反射获取函数参数
        $clsInstance = new ReflectionClass($className);
        $fucIns      = $clsInstance->getMethod($fucName);

        //获取函数注释部分的描述
        $doc       = $fucIns->getDocComment();
        $paramDocs = $this->getDocs($doc);

        $paraNames = array();
        //获取函数体中参数说明
        $params = $fucIns->getParameters();
        foreach ($params as $param) {
            array_push($paraNames, $param->getName());
        }

        //todo 从缓存中获取已经反射够的注释解析结果

        return array('docs' => $paramDocs);

    }


}