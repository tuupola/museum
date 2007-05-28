--TEST--
MDB2_DataContainer error messages
--SKIPIF--
<?php 
/* first one for cvs */
include(dirname(__FILE__) . '/skipif.php');
if (@include(dirname(__FILE__)."/../DataContainer2.php")) {
    $status = ''; 
} else if (@include('MDB2/DataContainer2.php')) {
    $status = ''; 
} else {
    $status = 'skip';
}
print $status; 
?>
--FILE--
<?php 
require_once(dirname(__FILE__) . '/skipif.php');
require_once(dirname(__FILE__) . '/Person.php');
$params  = array();
$p = new Person($dbh, $params);
$p->createDB();
unset($p);

/* Container does not have a key. */
unset($params);
$params['first_name'] = 'Auto';
$params['last_name']  = 'Mansikka';
$p = new Person($dbh, $params);
$status = $p->load();
print $status->getMessage() . "\n";

/* No data found for key %s with value %s. */
unset($params);
$params['key']       = 'last_name';
$params['last_name']  = 'Kuuskajaskari';
$p = new Person($dbh, $params);
$status = $p->load();
print $status->getMessage() . "\n";

/* %d matches found. Using first match. */
unset($params);
$params['key']       = 'last_name';
$params['last_name']  = 'Tuupola';
$p = new Person($dbh, $params);
$status = $p->load();
print $status->getMessage() . "\n";



?>
--GET--
--POST--
--EXPECT--
Container does not have a key.
No data found for key last_name with value Kuuskajaskari.
2 matches found. Using first match.
