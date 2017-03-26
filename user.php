<?php


class user
{
    public $name;
    public $email;
    public $username;
    public $score;

    function __construct($name, $email, $username, $score)
    {
        $this->name = $name;
        $this->email = $email;
        $this->username = $username;
        $this->score = $score;
    }
}