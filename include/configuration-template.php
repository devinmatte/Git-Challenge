<?php

return (object) array(
	'host' => '127.0.0.1',
	'username' => 'root',
	'password' => '',
	'database' => 'gitchallenge',
	'git' => (object) array(
		'org' => 'github-tools',
		'client' => '',
		'secret' => ''
	),
	'options' => (object) array(
		'pool' => true,
		'debug' => false,
		'maxcalls' => 1000
	),
	'points' => (object) array(
		'additions' => 1,
		'deletions' => 1,
		'challenges' => 1,
		'commits' => 10,
		'issues' => 25,
		'pullRequests' => 50
	)
);
