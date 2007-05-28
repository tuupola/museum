--TEST--
MDB2_DataContainer::getObjects() LIMIT clause
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
$params  = array();
$p = new Person($dbh, $params);
$p->createDB();
unset($p);
unset($params);

$params['classname'] = 'person';
$params['limit']     = '3';
$person = Person::getObjects($dbh, $params);

foreach ($person as $p) {
    print "$p->id $p->first_name $p->last_name\n";
}
print "\n";

$params['limit']     = '0, 3';
$person = Person::getObjects($dbh, $params);

foreach ($person as $p) {
    print "$p->id $p->first_name $p->last_name\n";
}
print "\n";

$params['limit']     = '2, 2';
$person = Person::getObjects($dbh, $params);

foreach ($person as $p) {
    print "$p->id $p->first_name $p->last_name\n";
}
print "\n";

$params['limit']     = '3 2';
$person = Person::getObjects($dbh, $params);

foreach ($person as $p) {
    print "$p->id $p->first_name $p->last_name\n";
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

