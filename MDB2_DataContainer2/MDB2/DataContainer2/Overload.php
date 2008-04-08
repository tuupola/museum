<?php

/* $Id$ $ */

require_once 'MDB2/DataContainer2/Inflector.php';

class MDB2_DataContainer2_Overload {

    public function __call($method, $params) {

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
        } elseif (strpos($method, 'findBy') === 0) {
            $property =  MDB2_DataContainer2_Inflector::property(substr($method, 6));
            print $property;
            if (array_key_exists($property, $var)) {
                $this->$property = $params[0];
                $retval = null;
            }
        } elseif (strpos($method, 'findAllBy') === 0) {
            $property =  MDB2_DataContainer2_Inflector::property(substr($method, 9));
            print $property;
            if (array_key_exists($property, $var)) {
                $this->$property = $params[0];
                $retval = null;
            }
        }
        return($retval);  
    }
}