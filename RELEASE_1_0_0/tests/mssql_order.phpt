--TEST--
mssql DB_DataContainer::getObjects() ORDER clause
--SKIPIF--
<?php 
/* first one for cvs */
include('mssql.php');
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
require_once('mssql.php');
require_once('Person.php');
$params  = array();
$p = new Person($dbh, $params);
$p->createDB();
unset($p);
unset($params);

$params['strict']    = false;
$params['classname'] = 'person';
$params['order']     = "lastname, firstname";
$person = Person::getObjects($dbh, $params);

foreach ($person as $p) {
    print "$p->id $p->lastname $p->firstname\n";
}

?>
--GET--
--POST--
--EXPECT--
2 Kjellman Janne
4 Laine Jenni
3 Tuupola Juha-Matti
1 Tuupola Mika

