<?php

require(dirname(__FILE__) . '/vendor/autoload.php');
require(dirname(__FILE__) . '/config.php');

// Database configuration
ORM::configure('mysql:host=' . $CFG->dbhost . ';dbname=' . $CFG->dbname);
ORM::configure('username', $CFG->dbuser);
ORM::configure('password', $CFG->dbpass);

// GUMP setup
$gump = new GUMP();
$_POST = $gump->sanitize($_POST);
    
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

echo "this is the response\n";
