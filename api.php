<?php

// Headers allow cross-origin calls
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

require(dirname(__FILE__) . '/vendor/autoload.php');
require(dirname(__FILE__) . '/config.php');

// Database configuration
ORM::configure('mysql:host=' . $CFG->dbhost . ';dbname=' . $CFG->dbname);
ORM::configure('username', $CFG->dbuser);
ORM::configure('password', $CFG->dbpass);
ORM::configure('return_result_sets', true);


//var_dump($_POST); die;

// GUMP setup
$gump = new GUMP();
$_POST = $gump->sanitize($_POST);
    
$gump->filter_rules([
    'action' => 'trim|sanitize_string',
]);
$data = $gump->run($_POST);
$data = (object)$data;
$action = $data->action;

// Instantiate class to execute requested action
$classname = "\\collection\\" . $action;
$process = new $classname($data);
echo $process->get();
