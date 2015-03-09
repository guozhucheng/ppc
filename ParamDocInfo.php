<?php

/**
 * 参数注释类 对应一条参数注释的基本属性
 * Created by guozhucheng@baidu.com
 * DateTime: 14-9-2 下午2:49
 */
class ParamDocInfo {
    /**
     * 参数名称
     * @var
     */
    private $_name;
    /**
     * 参数类型 包含常量中的定义的数据类型
     * @var
     */
    private $_type = null;

    /**
     * 参数是否可以为空
     * @var
     */
    private $_isNull = null;

    // 数据类型 types
    const TYPE_INT = 'int';
    const TYPE_INTEGER = 'intger';
    const TYPE_UINT = 'uint'; //无符号整数（大于等于0）
    const TYPE_FLOAT = 'float';
    const TYPE_DOUBLE = 'double';
    const TYPE_BOOL = 'bool';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_STRING = 'string';
    const TYPE_OBJECT = 'object';
    const TYPE_DATE = 'date';
    const TYPE_ARRAY = 'array';

    //是否可以为null
    const  NULLABLE = 'null';
    const  NOTNULL = 'notnull';

    /**
     * 构造函数
     * @param string $paramStr 注释分割后的一条记录如 string notnull $p1
     */
    function __construct($paramStr) {
        //均按照小写来处理
        $paramStr = strtolower($paramStr);
        $filters  = preg_split('/[\s]+/', $paramStr);

        foreach ($filters as $filter) {
            //当前filter是参数名称
            if (!empty($filter) && substr($filter, 0, 1) == '$') {
                $this->_name = trim($filter, '$');
            } else {
                switch ($filter) {
                    //region 构造类型
                    case self::TYPE_INT:
                    case self::TYPE_INTEGER:
                        //进行空判断 防止重复赋值
                        if ($this->_type == null) {
                            $this->_type = self::TYPE_INT;
                        }
                        break;
                    case self::TYPE_UINT:
                        if ($this->_type == null) {
                            $this->_type = self::TYPE_UINT;
                        }
                        break;
                    case self::TYPE_FLOAT:
                        if ($this->_type == null) {
                            $this->_type = self::TYPE_FLOAT;
                        }
                        break;
                    case self::TYPE_DOUBLE:
                        if ($this->_type == null) {
                            $this->_type = self::TYPE_DOUBLE;
                        }
                        break;
                    case self::TYPE_BOOL:
                    case self::TYPE_BOOLEAN:
                        if ($this->_type == null) {
                            $this->_type = self::TYPE_BOOL;
                        }
                        break;
                    case self::TYPE_STRING:
                        if ($this->_type == null) {
                            $this->_type = self::TYPE_STRING;
                        }
                        break;
                    case self::TYPE_OBJECT:
                        if ($this->_type == null) {
                            $this->_type = self::TYPE_OBJECT;
                        }
                        break;
                    case self::TYPE_DATE:
                        if ($this->_type == null) {
                            $this->_type = self::TYPE_DATE;
                        }
                        break;
                    case self::TYPE_ARRAY:
                        if ($this->_type == null) {
                            $this->_type = self::TYPE_ARRAY;
                        }
                        break;
                    //endregion

                    //region 构造校验对象
                    case self::NULLABLE:
                        if ($this->_isNull == null) {
                            $this->_isNull = true;
                        }
                        break;
                    case self::NOTNULL:
                        if ($this->_isNull == null) {
                            $this->_isNull = false;
                        }
                        break;
                    //endregion
                    default:
                        break;
                }
            }
        }
    }

    /**
     * 获取参数名称
     * @return string
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * 获取参数类型
     * @return null|string
     */
    public function getType() {
        return $this->_type;
    }

    /**
     * 判断是否为整数
     * @param $val
     * @return bool
     */
    private static function  isInt($val) {
        /*
         * 正则匹配是否为整数,整数的传入形 式
         * 1234 十进制数
         * -123 负数
         * 0123 八进制数 (等于十进制 83)
         * 0x1A(等于十进制 26)
         * 0b101(等于十进制5)
         */
        if (preg_match("/^-?\+?[1-9]\\d{0,19}$|^0$|^-?\+?0[1-7][0-7]{0,21}$|^-?\+?0[xX][1-9a-fA-F][0-9a-fA-F]{0,21}$|^-?\+?0b[0-1]{1,64}$/", $val)) {
            return true;
        }

        return false;
    }

    /**
     * 是否为无符号整数
     * @param $val
     * @return bool
     */
    private static function  isuInt($val) {
        if (self::isInt($val) && !preg_match("/^-*$/", $val)) {
            return true;
        }

        return false;
    }

    /**
     * 判断是否是浮点数
     * @param object $val 待验证值
     * @return bool
     */
    private static function isFloat($val) {
        if (preg_match("/^[+-]?(\d*\.\d+([eE]?[+-]?\d+)?|\d+[eE][+-]?\d+)$/", $val)) {
            return true;
        }

        return false;
    }

    /**
     * 判断是否是date类型
     * @param object $val 待验证值
     * 任何能通过strtotime函数转换为time格式的均能通过验证,参照http://jp1.php.net/manual/zh/datetime.formats.php说明
     * @return bool
     */
    private static function isDate($val) {
        return strtotime((string) $val) !== false;
    }


    /**
     * 判断参数值是否符合当前参数规则
     * @param $val
     * @return bool
     */
    public function  isLegal($val) {
        //参数不为空
        if ($this->_isNull === false) {
            if ($val === null) {
                return false;
            }
        }
        //参数可以为空
        if ($this->_isNull === true) {
            if ($val === null) {
                return true;
            }
        }
        switch ($this->_type) {
            //region 构造类型
            case self::TYPE_INT:
            case self::TYPE_INTEGER:
                if (!$this->isInt($val)) {
                    return false;
                }
                break;
            case self::TYPE_UINT:
                if (!$this->isuInt($val)) {
                    return false;
                }
                break;
            case self::TYPE_FLOAT:
                if (!$this->isFloat($val)) {
                    return false;
                }
                break;
            case self::TYPE_DOUBLE:
                if (!is_double($val)) {
                    return false;
                }
                break;
            case self::TYPE_BOOL:
            case self::TYPE_BOOLEAN:
                if (!is_bool($val)) {
                    return false;
                }
                break;
            case self::TYPE_STRING:
                if (!is_string($val)) {
                    return false;
                }
                break;
            case self::TYPE_OBJECT:
                if (!is_object($val)) {
                    return false;
                }
                break;
            case self::TYPE_DATE:
                if (!$this->isDate($val)) {
                    return false;
                }
                break;
            case self::TYPE_ARRAY:
                if (!is_array($val)) {
                    return false;
                }
                break;
            default:
                break;
        }

        return true;
    }
}



