<?php

class test extends PHPUnit_Framework_TestCase
{
    public function testFailingInclude()
    {
        include 'alert.php';
    }

    public function testConfiguration()
    {
        include 'include/configuration.php';
    }
}

?>