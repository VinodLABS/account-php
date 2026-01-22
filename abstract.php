<?php
abstract class car {
    public $name;

    public function __construct($name)
    {
        $this->name = $name;

    } 
    abstract public function intro();
}

class Audi extends car {

public function intro(){
   return "hii i am abstracted from parent class my name is " . $this->name;
}

}

$audi = new Audi("audi");
 echo $audi->intro();
?>