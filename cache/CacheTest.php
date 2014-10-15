<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14-10-14
 * Time: 下午10:53
 */
require_once 'loader.php';

$cachInfo = new \cache\SimpleCache();
$cachInfo->addData('key_1', 'data_1', 10);
var_dump($cachInfo->getData('key_1'));