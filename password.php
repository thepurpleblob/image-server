<?php

require(dirname(__FILE__) . '/vendor/autoload.php');
require(dirname(__FILE__) . '/config.php');

// Database configuration
ORM::configure('mysql:host=' . $CFG->dbhost . ';dbname=' . $CFG->dbname);
ORM::configure('username', $CFG->dbuser);
ORM::configure('password', $CFG->dbpass);
ORM::configure('return_result_sets', true);

if (count($argv) != 3) {
    echo "usage:\n";
    echo "php password.php username password\n";
    die;
}

$username = $argv[1];
$password = $argv[2];

$user = ORM::for_table('srps_users')->where('username', $username)->find_one();
if (!$user) {
    echo "Username '$username' was not found\n";
}
$user->password = password_hash($password, PASSWORD_DEFAULT);
$user->save();
echo "Password has been updated for user '$username'\n";

