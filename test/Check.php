<?php

class InsClass {
    public $Name;

    public function __construct($name) {
        $this->Name = $name;
        printf("%s is created!\n", $this->Name);

    }

    public function  __destruct() {
        printf("%s is destroy!\n", $this->Name);
    }
}

class StaInsClass {
    private static $Insies = array();

    public function  __construct($name) {
        if (!array_key_exists($name, self::$Insies)) {
            array_push(self::$Insies, new InsClass($name));
        }
    }

    public function __destruct() {
        printf("insies now is %s\n", json_encode(self::$Insies));
        printf("StaInsClass is destory!\n");
    }
}

function Case3() {
    $case3 = new StaInsClass('name3');

    return null;
}

$case3 = Case3();
$case1 = new StaInsClass('name1');
$case2 = new StaInsClass('name2');

printf("Ready to end\n");


