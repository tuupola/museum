--TEST--
DB_DataContainer::setProperties()
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
$params['strict']    = false;
$p = new Person($dbh, $params);
unset($params);
$params['firstname'] = 'Mika';
$params['lastname']  = 'Tuupola';
$params['mobile']    = '+358-50-1234567';
$p->setProperties($params);
print_r($p);
?>
--GET--
--POST--
--EXPECT--
person Object
(
    [firstname] => Mika
    [lastname] => Tuupola
    [mobile] => +358-50-1234567
    [id] => 
    [dbh] => 
    [table] => person
    [key] => 
    [strict] => 
)
