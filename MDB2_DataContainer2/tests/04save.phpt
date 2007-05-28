--TEST--
MDB2_DataContainer::save()
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
  
$params['strict']    = false;
$params['first_name'] = 'Sami';
$params['last_name']  = 'Johansson';
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
print $p->first_name . "\n";
print $p->last_name . "\n";
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
print $p->first_name . "\n";
print $p->last_name . "\n";
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

