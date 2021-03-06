<?php

class Connection
{
    public function initialize()
    {
        if (file_exists("include/configuration.php")) {
            $configs = include("include/configuration.php");
        } else {
            $configs = include("include/configuration-template.php");
        }
        require("alert.php");

        /** Create connection */
        $conn = new mysqli($configs->host, $configs->username, $configs->password);
        $alert = new Alert;

        /** Check connection */
        if ($conn->connect_error) {
            die("<div class=\"alert alert-danger alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a><b>Connection failed:</b> " . $conn->connect_error . "</div>");
        }

        $conn = new mysqli($configs->host, $configs->username, $configs->password, $configs->database);

        /** sql to create table */
        $sql = "CREATE TABLE Tracked (sha VARCHAR(256), issueID VARCHAR(256))";

        if ($conn->query($sql) === TRUE) {
            $message = "Table Tracked created successfully";
            $alert->success($message);
        }

        /** sql to create table */
        $sql = "CREATE TABLE Users (name VARCHAR(256) NOT NULL, username VARCHAR(128) NOT NULL, id INT(35) NOT NULL, score INT(25) DEFAULT 0, added INT(25) DEFAULT 0, removed INT(25) DEFAULT 0, challenge INT(25) DEFAULT 0, commits INT(25) DEFAULT 0, issues INT(25) DEFAULT 0, pullRequests INT(25) DEFAULT 0)";

        if ($conn->query($sql) === TRUE) {
            $message = "Table Users created successfully";
            $alert->success($message);
        }

        /** sql to create table */
        $sql = "CREATE TABLE Stats (repository VARCHAR(256), commits INT(25) DEFAULT 0)";

        if ($conn->query($sql) === TRUE) {
            $message = "Table Stats created successfully";
            $alert->success($message);
        }

        if ($configs->options->event == true) {
            /** sql to create table */
            $sql = "CREATE TABLE Events (prize VARCHAR(256))";

            if ($conn->query($sql) === TRUE) {
                $message = "Table Events created successfully";
                $alert->success($message);
            }
        }

        if ($configs->options->challenges == true) {
            /** sql to create table */
            $sql = "CREATE TABLE Challenges (id INT(25) NOT NULL, name VARCHAR(256), description VARCHAR(5000), points INT(25), hint VARCHAR(256), users VARCHAR(1024))";

            if ($conn->query($sql) === TRUE) {
                $message = "Table Challenges created successfully";
                $alert->success($message);
            }
        }

        return $conn;
    }
}