<?php

use PHPUnit\Framework\TestCase;

class test7 extends TestCase
{
    public function testConnection()
    {
        if (file_exists("include/configuration.php")) {
            include("include/configuration.php");
        } else {
            include("include/configuration-template.php");
        }
        require("connection.php");
        $connection = new Connection;
        $connection->initialize();
    }
}

?>