<?php

/* $Id: Overload.php,v 1.4 2004/10/17 00:29:14 tuupola Exp $ */

/* This part is basically taken from Alan Knowles' DB_DataObject */

/*
+-----------------------------------------------------------------------+
| Copyright (c) 2002-2007, Mika Tuupola                                 |
| All rights reserved.                                                  |
|                                                                       |
| Redistribution and use in source and binary forms, with or without    |
| modification, are permitted provided that the following conditions    |
| are met:                                                              |
|                                                                       |
| o Redistributions of source code must retain the above copyright      |
|   notice, this list of conditions and the following disclaimer.       |
| o Redistributions in binary form must reproduce the above copyright   |
|   notice, this list of conditions and the following disclaimer in the |
|   documentation and/or other materials provided with the distribution.|
| o The names of the authors may not be used to endorse or promote      |
|   products derived from this software without specific prior written  |
|   permission.                                                         |
|                                                                       |
| THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS   |
| "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT     |
| LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR |
| A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT  |
| OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, |
| SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT      |
| LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, |
| DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY |
| THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT   |
| (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE |
| OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.  |
|                                                                       |
+-----------------------------------------------------------------------+
| Authors: Alan Knowles <alan@akbkhome.com>                             |
|          Mika Tuupola <tuupola@appelsiini.net>                        |
|          Toni Viemerö <toni.viemero@iki.fi>                           |
+-----------------------------------------------------------------------+

*/

if (version_compare(phpversion(), '5.0.0', 'ge')) {

    class MDB2_DataContainer_Overload {
        function __call($method,$args) {
            $retval = null;
            $this->___call($method,$args,$retval);
            return($retval);
        }
    }

} else {

    eval('
        class MDB2_DataContainer_Overload {
            function __call($method,$args,&$retval) {
                return($this->___call($method,$args,$retval));
            }
        }
    ');

}

?>
