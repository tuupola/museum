--TEST--
MDB2_DataContainer::delete()
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
unset($params);

$params['id'] = 1;

$p = new Person($dbh, $params);
$status = $p->delete();

if (PEAR::isError($status)) {
   print $status->getMessage();
} else {
   print "OK\n...\n";
}

$params2 = array();
$p2 = new Person($dbh, $params2);
$status = $p2->delete();

if (PEAR::isError($status)) {
   print $status->getMessage();
} else {
   print "OK\n";
}

?>
--GET--
--POST--
--EXPECT--
OK
...
Object does not have an id.

