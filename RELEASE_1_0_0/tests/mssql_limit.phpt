--TEST--
mssql DB_DataContainer::getObjects() LIMIT clause
--SKIPIF--
<?php 
include('mssql.php');
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
require_once('mssql.php');
require_once('Person.php');
$params  = array();
$p = new Person($dbh, $params);
$p->createDB();
unset($p);
unset($params);

$params['strict']    = false;
$params['classname'] = 'person';
$params['limit']     = '3';
$person = Person::getObjects($dbh, $params);

foreach ($person as $p) {
    print "$p->id $p->firstname $p->lastname\n";
}
print "\n";

$params['limit']     = '0, 3';
$person = Person::getObjects($dbh, $params);

foreach ($person as $p) {
    print "$p->id $p->firstname $p->lastname\n";
}
print "\n";

$params['limit']     = '2, 2';
$person = Person::getObjects($dbh, $params);

foreach ($person as $p) {
    print "$p->id $p->firstname $p->lastname\n";
}
print "\n";

$params['limit']     = '3 2';
$person = Person::getObjects($dbh, $params);

foreach ($person as $p) {
    print "$p->id $p->firstname $p->lastname\n";
}
print "\n";

?>
--GET--
--POST--
--EXPECT--
1 Mika Tuupola
2 Janne Kjellman
3 Juha-Matti Tuupola

1 Mika Tuupola
2 Janne Kjellman
3 Juha-Matti Tuupola

3 Juha-Matti Tuupola
4 Jenni Laine

4 Jenni Laine

