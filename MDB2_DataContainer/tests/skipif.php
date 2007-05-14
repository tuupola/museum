<?php

include('./setup.php');
require_once('MDB2.php');

if (!defined('DRIVER_DSN') || DRIVER_DSN == '') {
    die("skip\n");
}

$dbh =& MDB2::factory(DRIVER_DSN);
if (MDB2::isError($dbh)) {
    die("skip\n");
}

?>
