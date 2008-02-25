<?php

/**
 * Simplest inflector in the world.
 */

class MDB2_DataContainer2_Inflector {

    public function __construct() {
    }
    
    static function pluralize($word) {
        $pluralized = $word . "s";
        return $pluralized;
    }
    
    static function singularize($word) {
        $singularized = substr($word, 0, -1); 
        return $singularized;
    }

    static function camelize($word) {
        $camelized = str_replace(" ", "", ucwords(str_replace("_", " ", $word)));
        return $camelized;
    }
    
    static function underscore($word) {
        $underscored = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $word));
        $underscored = strtolower(preg_replace('/([0-9])([a-z])/', '\\1_\\2', $underscored));        
        $underscored = strtolower(preg_replace('/([a-z])([0-9])/', '\\1_\\2', $underscored));        
        return $underscored;
    }
    
    static function humanize($word) {
        $humanized = ucwords(str_replace("_", " ", $word));
        return $humanized;
    }
    
    static function classify($table) {
        $classified = MDB2_DataContainer2_Inflector::camelize(MDB2_DataContainer2_Inflector::singularize($table));
        return $classified;
    }
    
    static function tableize($class) {
        $tableized = MDB2_DataContainer2_Inflector::pluralize(MDB2_DataContainer2_Inflector::underscore($class));
        return $tableized;
    }
    
    static function variable($string) {
        $variable = MDB2_DataContainer2_Inflector::underscore($string);
        return $variable;
    }

    static function property($string) {
        $property = MDB2_DataContainer2_Inflector::underscore($string);
        return $property;
    }

}