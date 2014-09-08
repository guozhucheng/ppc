<?php

/**
 * Created by guozhucheng@baidu.com
 * DateTime: 14-8-31 上午1:58
 */
class TestClass {

    /** 被测函数
     * @param int  $p1 [notempty]
     * @param      string notnull $p2
     * @param date $p3
     * @return  array
     */
    public function  testFuc1($p1, $p2, $p3) {
        return array('testres' => 'test');
    }
}
