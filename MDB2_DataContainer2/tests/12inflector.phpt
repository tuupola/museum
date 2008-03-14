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

print MDB2_DataContainer2_Inflector::pluralize("foo") . "\n";
print MDB2_DataContainer2_Inflector::camelize("foo_bar") . "\n";
print MDB2_DataContainer2_Inflector::underscore("FooBar") . "\n";
print MDB2_DataContainer2_Inflector::underscore("Foo_Bar") . "\n";
print MDB2_DataContainer2_Inflector::humanize("foo_bar") . "\n";
print MDB2_DataContainer2_Inflector::classify("foo_bars") . "\n";
print MDB2_DataContainer2_Inflector::tableize("FooBar") . "\n";
print MDB2_DataContainer2_Inflector::tableize("Foo_Bar") . "\n";
print MDB2_DataContainer2_Inflector::variable("FooBar") . "\n";
print MDB2_DataContainer2_Inflector::property("FooBar") . "\n";
?>
--GET--
--POST--
--EXPECT--
foos
FooBar
foo_bar
foo_bar
Foo Bar
FooBar
foo_bars
foo_bars
foo_bar
foo_bar

