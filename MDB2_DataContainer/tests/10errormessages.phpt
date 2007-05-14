--TEST--
MDB2_DataContainer error messages
--SKIPIF--
<?php 
/* first one for cvs */
include('./skipif.php');
if (@include(dirname(__FILE__)."/../DataContainer.php")) {
    $status = ''; 
} else if (@include('MDB2/DataContainer.php')) {
    $status = ''; 
} else {
    $status = 'skip';
}
print $status; 
?>
--FILE--
<?php 
require_once('./skipif.php');
require_once('./Person.php');
$params  = array();
$p = new Person($dbh, $params);
$p->createDB();
unset($p);

/* Container does not have a key. */
unset($params);
$params['firstname'] = 'Auto';
$params['lastname']  = 'Mansikka';
$p = new Person($dbh, $params);
$status = $p->load();
print $status->getMessage() . "\n";

/* No data found for key %s with value %s. */
unset($params);
$params['key']       = 'lastname';
$params['lastname']  = 'Kuuskajaskari';
$p = new Person($dbh, $params);
$status = $p->load();
print $status->getMessage() . "\n";

/* %d matches found. Using first match. */
unset($params);
$params['key']       = 'lastname';
$params['lastname']  = 'Tuupola';
$p = new Person($dbh, $params);
$status = $p->load();
print $status->getMessage() . "\n";



?>
--GET--
--POST--
--EXPECT--
Container does not have a key.
No data found for key lastname with value Kuuskajaskari.
2 matches found. Using first match.
