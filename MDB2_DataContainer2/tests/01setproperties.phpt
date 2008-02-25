--TEST--
MDB2_DataContainer::setProperties()
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
unset($params);
$params['first_name'] = 'Mika';
$params['last_name']  = 'Tuupola';
$params['mobile']    = '+358-50-1234567';
$p->setProperties($params);
print_r($p);
?>
--GET--
--POST--
--EXPECT--
person Object
(
    [first_name] => Mika
    [last_name] => Tuupola
    [mobile] => +358-50-1234567
    [id] => 
    [dbh] => 
    [table] => persons
    [key] => 
)
