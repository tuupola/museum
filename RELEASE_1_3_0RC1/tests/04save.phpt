--TEST--
DB_DataContainer::save()
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
  
$params['strict']    = false;
$params['firstname'] = 'Sami';
$params['lastname']  = 'Johansson';
$params['mobile']    = '+358-31-123456';
$p = new Person($dbh, $params);
$p->createDB();
$p->save();

$id = $p->getId();
unset($p);
unset($params);

$params['id']      = $id;
$params['strict']  = false;
  
$p = new Person($dbh, $params);
$p->load();

print $p->id . "\n";
print $p->firstname . "\n";
print $p->lastname . "\n";
print $p->mobile . "\n";

$p->mobile = '+358-50-987654';
$p->save();

unset($p);
unset($params);

$params['id']      = $id;
$params['strict']  = false;
  
$p = new Person($dbh, $params);
$p->load();

print $p->id . "\n";
print $p->firstname . "\n";
print $p->lastname . "\n";
print $p->mobile . "\n";

?>
--GET--
--POST--
--EXPECT--
5
Sami
Johansson
+358-31-123456
5
Sami
Johansson
+358-50-987654

