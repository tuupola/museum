<?php

/* vim: set ts=4 sw=4: */

// +-----------------------------------------------------------------------+
// | Copyright (c) 2002-2003, Mika Tuupola                                 |
// | All rights reserved.                                                  |
// |                                                                       |
// | Redistribution and use in source and binary forms, with or without    |
// | modification, are permitted provided that the following conditions    |
// | are met:                                                              |
// |                                                                       |
// | o Redistributions of source code must retain the above copyright      |
// |   notice, this list of conditions and the following disclaimer.       |
// | o Redistributions in binary form must reproduce the above copyright   |
// |   notice, this list of conditions and the following disclaimer in the |
// |   documentation and/or other materials provided with the distribution.|
// | o The names of the authors may not be used to endorse or promote      |
// |   products derived from this software without specific prior written  |
// |   permission.                                                         |
// |                                                                       |
// | THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS   |
// | "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT     |
// | LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR |
// | A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT  |
// | OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, |
// | SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT      |
// | LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, |
// | DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY |
// | THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT   |
// | (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE |
// | OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.  |
// |                                                                       |
// +-----------------------------------------------------------------------+
// | Author: Mika Tuupola <tuupola@appelsiini.net>                         |   
// +-----------------------------------------------------------------------+

/* $Id$ */

require_once('DB.php');
require_once('PEAR.php');

/**
  * DB_DataContainer class
  *
  * @version	$Revision$
  * @author	Mika Tuupola <tuupola@appelsiini.net>
  */  


class DB_DataContainer {

  /**
    * Id of the data if exists the database. 
    *
    * Should only have value only if the data was read from the 
    * database or if you are updating existing object.
    *
    * @access   private
    */
    var $id;           

   /**
    * PEAR database handler
    * @accesss  private
    * @see      DB
    */
    var $dbh;
 
   /**
    * Table in which data is stored
    * @access   private
    */
    var $table;

   /**
    * What column to use as non-default key
    * @access   private
    */
    var $key;
          
   /**
    * The class constructor
    *
    * If $params[id] is given will create the dataobject from
    * data queried from database. Otherwise will try to create
    * the object from given array of.
    *
    * If $params[key] is given that column will be used instead
    * of column 'id'. 
    *
    * $params[$params[key]] _must_ be given together with $params[key]
    *
    * $params[table] is mandatory
    *
    * Rest of the data given in $params will be mapped against
    * object properties.
    *
    * @param	object  $dbh a PEAR database handler object.
    * @param	array   $params 
    * @return	object
    */  


    function DB_DataContainer($dbh, $params, $strict=true) {

        $this->dbh   = $dbh;
        $this->setProperties($params, $strict);
        
    }


    function load() {

        $result = true;

        /* if we have an id or key load up data from the database  */
        /* and discard any possible data given in $params.         */
        /* id overrides anything                                   */
        if ($this->id) {
            $this->key = 'id';
        }
        if ($this->key) {
            $query  = "SELECT * FROM $this->table
                       WHERE ($this->key='{$this->{$this->key}}') ";

            // print "<FONT COLOR=#FF0000>$query</FONT><BR>";  

            $result = $this->dbh->query($query);
            if (DB::isError($result)) {
               //  print $result->getDebugInfo(); 
            } else {
                $row = $result->fetchRow(DB_FETCHMODE_ASSOC);
                if (is_array($row)) {
                    foreach ($row as $key=>$val) {
                        $this->$key = $val;
                    }
                }
            }
        } else {
            $result = PEAR::raiseError('Object does not have a key.');
        }
        return($result);
    }

  /**
    * Save the object
    *
    * If $id is not given will use the objects own id. If $id not
    * given and object does not have an $id will set the $id when
    * executing the INSERT.
    *
    * @param	integer $id (optional)
    * @return	mixed true on success PEAR error on failure
    */  

    function save($id='') {
             
        // magic, if $id was given as a parameter, set my id to it and
        // because $id is set we will do an UPDATE. TODO: This will still
        // fail if $id does not exist.
             
        if ($id) {  
             $this->id = $id;
             
        // if $id was not given try to use objects own id. If even that
        // does not exist the object must have been created from submitted
        // data and the $id will be empty -> we must do an INSERT.
             
        } else {
             $id       = $this->id;
        }          
        
        // Of object has an id this has to be an UPDATE
        if ($id) { 
    
            $prepend = "UPDATE $this->table SET ";
            $append  = " WHERE (id='$this->id') ";
        
        } else {
            
            $this->id  = $this->dbh->nextID($this->table);
            $prepend = "INSERT INTO $this->table SET
                        id='$this->id', ";
            $append  = " ";
        }
             
        $query  = $prepend;

        $var    = get_object_vars($this);

        /* This wont work prior to PHP 4.2.0 */
        $ignore = get_class_vars('DB_DataContainer');       
        foreach($ignore as $key => $val) {
            unset($var[$key]);
        }

        foreach ($var as $key => $value) {
            if ($value) {
                $query .= "$key='$value', ";
            }
        }

        $query = substr($query, 0, -2); 

        $query .= $append;

        // print "<FONT COLOR=#FF0000>$query</FONT><BR>";

        $result = $this->dbh->query($query);

        return($result);
    }

  /**
    * Delete the object
    *
    * @return	mixed true on success PEAR error on failure
    */  

    function delete() {
        if ($this->id) { 
            $query = "DELETE FROM $this->table WHERE id='$this->id' ";
            $retval = $this->dbh->query($query);
        } else {
            $retval = PEAR::raiseError('Object does not have an id.');
        }
     
        return($retval);
    } 


   /**
    * Set class properties
    *
    * @access public
    * @param  array  $params
    */

    function setProperties($params, $strict=true) {
        if (is_array($params)) {

            /* use accessor methods */
            if ($strict) {
                foreach ($params as $key => $value) {
                    $method = 'set' . $key;
                    $this->$method($value);
                }
                
            /* dont use accessor methods */
            } else {
                foreach ($params as $key => $value) {
                    $method = 'set' . $key;
                    $this->$method($value);
                }
            }
        }
    }

  /**
    * Get the id
    *
    * @return    integer
    */  
    function getId() {
        return($this->id);
    }

  /**
    * Get the database handle
    *
    * @return    object 
    */  
    function getDbh() {
        return($this->dbh);
    }

  /**
    * Get the table name
    *
    * @return    string 
    */  
    function getTable() {
        return($this->table);
    }

    function setId($value) {
        $this->id = $value;
    }

    function setKey($value) {
        $this->key = $value;
    }

    function setTable($value) {
        $this->table = $value;
    }


  /**
    * Get array of DataObjects
    *
    * If $params[where] is given it will added to sql query
    * as WHERE $params[where].
    *
    * @param	object  $dbh a PEAR database handler object.
    * @param	array   $params (table, classname, where, order, autoload)
    * @access	static
    * @return	mixed   array of objects on success PEAR_Error on failure
    */  

    /* TODO: Classname still needs to be given as parameter        */
    /*       until there is a way for static method to determine  */
    /*       which class it belongs to. Make this more elegant.   */

    function getObjects($dbh, $params='') {

        if (!(trim($params['classname']))) {
            $retval = PEAR::raiseError('Need $params[classname]');
        } elseif (!(trim($params['table']))) {  
              /* defaults to $params[classname] */
              $params['table'] = $params[classname];
        }

        if (!(PEAR::isError($retval))) {
        
            /* if we have an hardcoded query no need to generate one */
            if (isset($params['query'])) {

                $query = $params['query'];

            } else {

                $query = "SELECT * FROM $params[table] ";
                if (isset($params['where'])) {
                    $query .= "WHERE $params[where] ";
                }
                if (isset($params['orderby'])) {
                    $query .= "ORDER BY $params[orderby] ";
                } elseif (isset($params['order'])) {
                    $query .= "ORDER BY $params[order] ";
                }
                if (isset($params['limit'])) {
                    $query .= "LIMIT $params[limit] ";
                }
            }

            $result = $dbh->query($query);

            if (DB::isError($result)) {
                $retval = $result;
            } else { 
                $retval = array();

                while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
                    $row['table'] = $params['table'];
                    $c = new $params['classname']($dbh, $row);
                    array_push($retval, $c);
                    unset($c);
                }
            }
        }
        return($retval);     
    }        

}

?>
