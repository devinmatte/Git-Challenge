<?php

///////////////////////////////////////////////////////
///                                                 ///
/// Git Challenge Configuration                     ///
///                                                 ///
/// @author Devin Matte <devinmatte@gmail.com>      ///
///                                                 ///
///////////////////////////////////////////////////////

$get_data = function(&$var, $default=null) {
    return isset($var) ? $var : $default;
};

return (object)array(
    'host' => $get_data($_ENV['CHALLENGE_DB_HOST'], '127.0.0.1'),
    'username' => $get_data($_ENV['CHALLENGE_DB_USER'], 'root'),
    'password' => $get_data($_ENV['CHALLENGE_DB_PASS'], ''),
    'database' => $get_data($_ENV['CHALLENGE_DB'], 'gitchallenge'),
    'git' => (object)array(
        'org' => $get_data($_ENV['CHALLENGE_GITHUB_ORG'], 'github-tools'),
        'client' => $get_data($_ENV['CHALLENGE_GITHUB_CLIENT'], ''),
        'secret' => $get_data($_ENV['CHALLENGE_GITHUB_SECRET'], '')
    ),
    'options' => (object)array(
        'pool' => $get_data($_ENV['CHALLENGE_POOL'], true),
        'debug' => $get_data($_ENV['CHALLENGE_DEBUG'], false),
        'event' => $get_data($_ENV['CHALLENGE_EVENT'], false),
        'maxcalls' => $get_data($_ENV['CHALLENGE_MAXCALLS'], 1000)
    ),
    'points' => (object)array(
        'additions' => 1,
        'deletions' => 1,
        'challenges' => 1,
        'commits' => 10,
        'issues' => 25,
        'pullRequests' => 50
    )
);
