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

$CHALLENGE_DB_HOST = getenv('CHALLENGE_DB_HOST');
$CHALLENGE_DB_USER = getenv('CHALLENGE_DB_USER');
$CHALLENGE_DB_PASS = getenv('CHALLENGE_DB_PASS');
$CHALLENGE_DB = getenv('CHALLENGE_DB');
$CHALLENGE_GITHUB_ORG = getenv('CHALLENGE_GITHUB_ORG');
$CHALLENGE_GITHUB_CLIENT = getenv('CHALLENGE_GITHUB_CLIENT');
$CHALLENGE_GITHUB_SECRET = getenv('CHALLENGE_GITHUB_SECRET');
$CHALLENGE_POOL = getenv('CHALLENGE_POOL');
$CHALLENGE_DEBUG = getenv('CHALLENGE_DEBUG');
$CHALLENGE_EVENT = getenv('CHALLENGE_EVENT');
$CHALLENGE_CHALLENGES = getenv('CHALLENGE_CHALLENGES');
$CHALLENGE_INFO = getenv('CHALLENGE_INFO');
$CHALLENGE_MAXCALLS = getenv('CHALLENGE_MAXCALLS');

return (object)array(
    'host' => $get_data($CHALLENGE_DB_HOST, '127.0.0.1'),
    'username' => $get_data($CHALLENGE_DB_USER, 'root'),
    'password' => $get_data($CHALLENGE_DB_PASS, ''),
    'database' => $get_data($CHALLENGE_DB, 'gitchallenge'),
    'git' => (object)array(
        'org' => $get_data($CHALLENGE_GITHUB_ORG, 'github-tools'),
        'client' => $get_data($CHALLENGE_GITHUB_CLIENT, ''),
        'secret' => $get_data($CHALLENGE_GITHUB_SECRET, '')
    ),
    'options' => (object)array(
        'pool' => $get_data($CHALLENGE_POOL, true),
        'debug' => $get_data($CHALLENGE_DEBUG, false),
        'event' => $get_data($CHALLENGE_EVENT, false),
        'challenges' => $get_data($CHALLENGE_CHALLENGES, false),
        'info' => $get_data($CHALLENGE_INFO, true),
        'maxcalls' => $get_data($CHALLENGE_MAXCALLS, 1000)
    ),
    'points' => (object)array(
        'additions' => 1,
        'deletions' => 1,
        'challenges' => 1,
        'commits' => 10,
        'issues' => 25,
        'pullRequests' => 50
    ),
    'blacklist' => (object)array(
        'invalid-email-address' => 148100
    )
);

