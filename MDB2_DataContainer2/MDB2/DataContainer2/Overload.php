<?php

require_once 'Inflector.php';

class MDB2_DataContainer2_Overload {

    function __call($method, $params) {

        $var     = get_object_vars($this);
        $retval = false;

        if (strpos($method, 'get') === 0) {
            $property =  MDB2_DataContainer2_Inflector::property(substr($method, 3));
            if (array_key_exists($property, $var)) {
                $retval = $this->$property;
            }
        } elseif (strpos($method, 'set') === 0) {
            $property =  MDB2_DataContainer2_Inflector::property(substr($method, 3));
            if (array_key_exists($property, $var)) {
                $this->$property = $params[0];
                $retval = null;
            }
        }
        return($retval);  
    }
}