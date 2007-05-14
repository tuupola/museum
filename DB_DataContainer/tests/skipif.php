<?php

include('./setup.php');
require_once('DB.php');

if (!defined('DRIVER_DSN') || DRIVER_DSN == '') {
    die("skip\n");
}

$dbh = DB::connect(DRIVER_DSN);
if (DB::isError($dbh)) {
    die("skip\n");
}

?>
