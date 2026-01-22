<?php
abstract class car {
    public $name;

    public function __construct($name)
    {
        $this->name =- $name;

    } 
    abstract public function intro();

}

class Audi extends car {
public function intro(){
    echo "meowe";
}

}

$audi = new audi("Audi");
 echo $audi->intro();
?>