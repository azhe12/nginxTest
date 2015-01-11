<?php
class Person{
    var $age;
    function __construct($age)
    {
        $this->age = $age;
    }
    function output()
    {
        echo $this->age;
    }

    function setAge($age)
    {
        $this->age = $age;
    }
}
?>
