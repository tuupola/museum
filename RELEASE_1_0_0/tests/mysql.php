<?php

/* $Id$ */

require_once('DB.php');

$user = '';
$pass = '';
$host = 'localhost';
$db_name = 'test';
$dsn = "mysql://$user:$pass@$host/$db_name";

$dbh = DB::connect($dsn, true);
if (DB::isError($dbh)) { 
     print 'skip';
}


?>

