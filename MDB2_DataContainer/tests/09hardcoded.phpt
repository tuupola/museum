--TEST--
MDB2_DataContainer::getObjects() hardcoded query
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

$params['strict']    = false;
$params['classname'] = 'person';
$params['query']     = 'SELECT * FROM person ORDER BY id';
$person = Person::getObjects($dbh, $params);

foreach ($person as $p) {
    print "$p->id $p->lastname $p->firstname\n";
}

?>
--GET--
--POST--
--EXPECT--
1 Tuupola Mika
2 Kjellman Janne
3 Tuupola Juha-Matti
4 Laine Jenni

