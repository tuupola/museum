--TEST--
MDB2_DataContainer overloading
--SKIPIF--
<?php 
include(dirname(__FILE__) . '/skipif.php');
/* first one for cvs */
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

$p->findByFirstName();
$p->findAllByFirstName();
?>
--GET--
--POST--
--EXPECT--
Mika
Tuupola
+358-50-1234567
example

