--TEST--
MDB2_DataContainer::getObjects() hardcoded query
--SKIPIF--
<?php 
/* first one for cvs */
include(dirname(__FILE__) . '/skipif.php');
if (@include(dirname(__FILE__) .  "/../DataContainer2.php")) {
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
$params['query']     = 'SELECT * FROM persons ORDER BY id';
$person = Person::find($dbh, $params);

foreach ($person as $p) {
    print "$p->id $p->last_name $p->first_name\n";
}

?>
--GET--
--POST--
--EXPECT--
1 Tuupola Mika
2 Kjellman Janne
3 Tuupola Juha-Matti
4 Laine Jenni

