--TEST--
MDB2_DataContainer constructor
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
$params = array();
$p = new Person($dbh, $params);
$p->createDB();
unset($p);

$p = new Person($dbh, 2);
$p->load();
print $p->getFirstName() . ' ' . $p->getLastName() . "\n";
unset($p);

$p = new Person($dbh, 1);
$p->load();
print $p->getFirstName() . ' ' . $p->getLastName() . "\n";
unset($p);

$params['id'] = 3;
$p = new Person($dbh, $params);
$p->load();
print $p->getFirstName() . ' ' . $p->getLastName() . "\n";
unset($p);
?>
--GET--
--POST--
--EXPECT--
Janne Kjellman
Mika Tuupola
Juha-Matti Tuupola

