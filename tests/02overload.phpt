--TEST--
DB_DataContainer overloading
--SKIPIF--
<?php 
include('./skipif.php');
/* first one for cvs */
if (@include(dirname(__FILE__)."/../DataContainer.php")) {
    $status = ''; 
} else if (@include('DB/DataContainer.php')) {
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
$dbh = '';
$params = array();
$p = new Person($dbh, $params);
$p->setFirstName('Mika');
$p->setLastName('Tuupola');
$p->setMobile('+358-50-1234567');
$p->setTable('example');
print $p->getFirstName() . "\n";
print $p->getLastName() . "\n";
print $p->getMobile() . "\n";
print $p->getTable() . "\n";
?>
--GET--
--POST--
--EXPECT--
Mika
Tuupola
+358-50-1234567
example

