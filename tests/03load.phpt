--TEST--
DB_DataContainer::load()
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
$params['id'] = 4;
$p = new Person($dbh, $params);
$p->createDB();
$status = $p->load();

if (PEAR::isError($status)) {
    print $status->getMessage();
} else {
    print $p->firstname . "\n";
    print $p->lastname . "\n";
    print $p->mobile . "\n";
    if (isset($p->nosuch)) {
      print $p->nosuch . "\n";
    }

    print_r($status);
}

print "\n\n";

unset($p);
$p = new Person($dbh, 1);
$status = $p->load();

if (PEAR::isError($status)) {
    print $status->getMessage();
} else {
    print $p->firstname . "\n";
    print $p->lastname . "\n";
    print $p->mobile . "\n";
    if (isset($p->nosuch)) {
      print $p->nosuch . "\n";
    }

    print_r($status);
}

?>
--GET--
--POST--
--EXPECT--
Jenni
Laine
+358-9-123456
1

Mika
Tuupola
+358-50-123456
1

