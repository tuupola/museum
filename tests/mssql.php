<?php

/* $Id$ */

require_once('DB.php');

$user = 'sa';
$pass = '';
$host = 'MSSQLSERVER';
$db_name = 'master';  
$dsn = "mssql://$user:$pass@$host/$db_name";     

$dbh = DB::connect($dsn, true);
if (DB::isError($dbh)) { 
     print 'skip';
}


?>

