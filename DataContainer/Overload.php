<?php

/* $Id$ */

if (version_compare(phpversion(), '5.0.0', 'ge')) {

    class DB_DataContainer_Overload {
        function __call($method,$args) {
            $retval = null;
            $this->___call($method,$args,$retval);
            return($retval);
        }
    }

} else {

    eval('
        class DB_DataContainer_Overload {
            function __call($method,$args,&$retval) {
                return($this->___call($method,$args,$retval));
            }
        }
    ');

}

?>

