<?php

/*
+-----------------------------------------------------------------------+
| Copyright (c) 2002-2003, Mika Tuupola                                 |
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
| Author: Mika Tuupola <tuupola@appelsiini.net>                         |
+-----------------------------------------------------------------------+
*/

/* $Id: IP2Country.php,v 1.5 2003/09/05 15:19:48 tuupola Exp $ */


require_once('DB.php');

class I18N_IP2Country {

    var $dbh;
    var $table;
    var $two;
    var $three;
    var $ip;
    var $long;

    function I18N_IP2Country($dbh, $ip, $params='') {

        $this->table = $params['table'] ? $params['table'] : 'ip2country';
        $this->dbh = $dbh;
        $this->setIp($ip);

    }

    function setIp($ip) {
        if (is_long($ip)) {
            $this->ip = $long2ip($ip);
        } else {

            /* do ve have a proper quaddotted address */
            if ($ip == long2ip(ip2long($ip))) {
                $this->ip = $ip;

            /* guess we have a canonical name then */
            } else {
                $this->ip = gethostbyname($ip);
            }
        }

        $this->setLong(ip2long($this->ip));

    }

    function getIp() {
        return($this->ip);
    }

    function setLong($input) {
        $this->long = $input;
    }

    function getLong() {
        return($this->long);
    }

    function setDbh($input) {
        $this->dbh = $input;
    }

    function setTwoLetterCode($input) {
        $this->two = $input;
    }

    function getTwoLetterCode() {
        return($this->two);
    }

    function setThreeLetterCode($input) {
        $this->three = $input;
    }

    function getThreeLetterCode() {
        return($this->three);
    }

    function getNumber() {
        require_once('I18N/ISO/3166.php');
        $retval = '';
        $i = new I18N_ISO_3166($this->two);
        $retval = $i->getNumber();
        return($retval);
    }

    function getCountry() {
        return($this->country);
    }

    function setCountry($input) {
        $this->country = $input;
    }

    function load() {
        if (is_object($this->dbh)) {
            $retval = $this->_loadDatabase();
        } else {
            $retval = $this->_loadCsv();
        }
        return($retval);
    }

    function _loadDatabase() {

        $retval = true;

        $unsigned = sprintf("%u", $this->long);
        $query = "SELECT country, two, three 
                  FROM $this->table 
                  WHERE $unsigned
                  BETWEEN ipfrom AND ipto ";
        $result = $this->dbh->getRow($query);

        if (PEAR::isError($result)) {
            $retval = false;
        } else {
            $this->setCountry($result[0]);
            $this->setTwoLetterCode($result[1]);
            $this->setThreeLetterCode($result[2]);
        }

        return($retval);

    }

    function _loadCsv() {

        $retval = false;
        
        /* TODO : this is extremely slow. Consider using serialized */
        /* array in tmpfs cache?                                    */
        $fd = fopen ($this->dbh, "r");
        while ($data = fgetcsv($fd, 1000, ",")) {
            $country[] = $data;
        }
        fclose($fd);

        $low  = 0;
        $high = count($country) - 1;

        $unsigned = sprintf("%u", $this->long);

        while ($low <= $high) {
           $mid = floor(($low + $high) / 2);
           if ($unsigned >= $country[$mid][0] && $unsigned <= $country[$mid][1]) {
                $retval = true;
                $this->setTwoLetterCode($country[$mid][2]);
                $this->setThreeLetterCode($country[$mid][3]);
                $this->setCountry($country[$mid][4]);
                break;
            } else if ($unsigned < $country[$mid][0]) {
                $high = $mid - 1;
            } else {
                $low = $mid + 1;
            }
        }
  
        return($retval);

    }

}

?>
