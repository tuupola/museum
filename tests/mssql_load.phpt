--TEST--
mssql DB_DataContainer::load()
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
require_once('mysql.php');
require_once('Person.php');
$params['id'] = 4;
$p = new Person($dbh, $params);
$p->createDB();
$p->load();

print $p->firstname . "\n";
print $p->lastname . "\n";
print $p->mobile . "\n";

?>
--GET--
--POST--
--EXPECT--
Jenni
Laine
+358-9-123456

