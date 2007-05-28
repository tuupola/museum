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
        return $underscored;
    }
    
    static function humanize($word) {
        $humanized = ucwords(str_replace("_", " ", $word));
        return $humanized;
    }
    
    static function classify($table) {
        $classified = Inflector::camelize(Inflector::singularize($table));
        return $classified;
    }
    
    static function tableize($class) {
        $tableized = Inflector::pluralize(Inflector::underscore($class));
        return $tableized;
    }
    
    static function variable($string) {
        $variable = Inflector::underscore($string);
        return $variable;
    }

    static function property($string) {
        $property = Inflector::underscore($string);
        return $property;
    }

}