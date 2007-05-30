--TEST--
MDB2_DataContainer::getObjects() WHERE clause
--SKIPIF--
<?php 
/* first one for cvs */
include(dirname(__FILE__) . '/skipif.php');
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
$params['where']     = "last_name='Tuupola'";
$person = Person::find($dbh, $params);

foreach ($person as $p) {
    print "$p->id $p->first_name $p->last_name\n";
}

?>
--GET--
--POST--
--EXPECT--
1 Mika Tuupola
3 Juha-Matti Tuupola

