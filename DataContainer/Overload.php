<?php

/* $Id$ */

if (version_compare(phpversion(), '5.0.0', 'ge')) {

    class DB_DataContainer_Overload {
        function __call($method,$args) {
            $return = null;
            $this->___call($method,$args,$return);
            return $return;
        }
    }

} else {

    eval('
        class DB_DataContainer_Overload {
            function __call($method,$args,&$return) {
                return $this->___call($method,$args,$return);
            }                               
        }
    ');

}

?>

