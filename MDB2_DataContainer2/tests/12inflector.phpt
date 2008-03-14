--TEST--
MDB2_DataContainer constructor
--SKIPIF--
<?php 
/* first one for cvs */
include(dirname(__FILE__) . '/skipif.php');
if (@include(dirname(__FILE__)."/../DataContainer.php")) {
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
if (! include(dirname(__FILE__)."/../MDB2/DataContainer2/Inflector.php")) {
  include('MDB2/DataContainer2/Inflector.php');
};

print MDB2_DataContainer2_Inflector::pluralize("foo");
print MDB2_DataContainer2_Inflector::camelize("foo_bar");
print MDB2_DataContainer2_Inflector::underscore("FooBar");
print MDB2_DataContainer2_Inflector::humanize("foo_bar");
print MDB2_DataContainer2_Inflector::classify("foo_bars");
print MDB2_DataContainer2_Inflector::tableize("FooBar");
print MDB2_DataContainer2_Inflector::tableize("Foo_Bar");
print MDB2_DataContainer2_Inflector::variable("FooBar");
print MDB2_DataContainer2_Inflector::property("FooBar");
?>
--GET--
--POST--
--EXPECT--
Janne Kjellman
Mika Tuupola
Juha-Matti Tuupola

