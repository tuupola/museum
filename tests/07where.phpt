--TEST--
DB_DataContainer::getObjects() WHERE clause
--SKIPIF--
<?php 
/* first one for cvs */
include('./skipif.php');
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
$params  = array();
$p = new Person($dbh, $params);
$p->createDB();
unset($p);
unset($params);

$params['classname'] = 'person';
$params['where']     = "lastname='Tuupola'";
$person = Person::getObjects($dbh, $params);

foreach ($person as $p) {
    print "$p->id $p->firstname $p->lastname\n";
}

?>
--GET--
--POST--
--EXPECT--
1 Mika Tuupola
3 Juha-Matti Tuupola

