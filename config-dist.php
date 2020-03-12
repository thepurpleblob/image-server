<?php

$CFG = new stdClass;

// Database credentials
$CFG->dbhost = 'localhost';
$CFG->dbname = 'collection';
$CFG->dbuser = 'username';
$CFG->dbpass = 'password';

// Directory to store images
$CFG->datadir = '/var/www/html/image-server/data';

// Directory contains test data for unit tests
$CFG->testdatadir = '/var/www/html/image-server/testdata';
