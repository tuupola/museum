<?php

/* vim: set ts=4 sw=4: */

/*
+-----------------------------------------------------------------------+
| Copyright (c) 2002-2003, Mika Tuupola                                 |
| All rights reserved.                                                  |
|                                                                       |
| Redistribution and use in source and binary forms, with or without    |
| modification, are permitted provided that the following conditions    |
| are met:                                                              |
|                                                                       |
| o Redistributions of source code must retain the above copyright      |
|   notice, this list of conditions and the following disclaimer.       |
| o Redistributions in binary form must reproduce the above copyright   |
|   notice, this list of conditions and the following disclaimer in the |
|   documentation and/or other materials provided with the distribution.|
| o The names of the authors may not be used to endorse or promote      |
|   products derived from this software without specific prior written  |
|   permission.                                                         |
|                                                                       |
| THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS   |
| "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT     |
| LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR |
| A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT  |
| OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, |
| SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT      |
| LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, |
| DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY |
| THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT   |
| (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE |
| OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.  |
|                                                                       |
+-----------------------------------------------------------------------+
| Author: Mika Tuupola <tuupola@appelsiini.net>                         |
+-----------------------------------------------------------------------+
*/

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
    * Flag whether were running on strict mode or not.
    * @access   private
    */
    var $strict;
          
  /**
    * The class constructor
    *
    * If $params is not an array but instead numeric, it will be 
    * considered as a shortcut to be $params[id]
    *
    * If $params[id] is given data can be load():ed from database.
    *
    * If $params[key] is given that column will be used  
    * instead of the default column named 'id' when load():ing data
    * from database.
    *
    * If $params[key] was given you also must provide $params[$params[key]].
    * For example if $params[key] = 'foo', you must give something
    * like $params[foo] = 27.
    *
    * $params[table] contains the name of the table in the database.
    * This parameter is mandatory.
    *
    * $params[strict] flag defines wheter to use accessor method
    * for setting object properties (the default) or set them by
    * directly accessing $this->property.
    *
    * Rest of the data given in $params will be mapped against
    * object properties.
    *
    * @param	object  $dbh a PEAR database handler object.
    * @param	mixed   $params numerical id or array of parameters
    * @return   object
    */  


    function DB_DataContainer($dbh, $params) {

        $strict = isset($params['strict']) ? $params['strict'] : true;
        $this->setStrict($strict);
        if (is_array($params)) {
            $this->setProperties($params);
        } else if (is_numeric($params)){
            $this->setId($params);
        }
        $this->setDBH($dbh);
    }

  /**
    * Load the container
    *
    * Populates the container object with data from database.
    * This is possible only if container has an id or an alternate
    * key and its value is available.
    *
    * @return   mixed true on success PEAR error on failure
    */  

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

            $result = $this->dbh->query($query);
            if (DB::isError($result)) {

            } else {
                $row = $result->fetchRow(DB_FETCHMODE_ASSOC);
                if (is_array($row)) {
                    foreach ($row as $key=>$val) {
                        /* TODO: ugly hack to fix mixed case trouble */
                        $key = strtolower($key);
                        $this->$key = $val;
                    }
                }
            }
        } else {
            $result = PEAR::raiseError('Container does not have a key.');
        }
        return($result);
    }

  /**
    * Save the container
    *
    * If $id is not given will use the objects own id. If $id not
    * given and object does not have an $id will set the $id when
    * executing the INSERT.
    *
    * @param	integer $id (optional)
    * @return   mixed true on success PEAR error on failure
    */  

    function save($id='') {
             
        /* magic, if $id was given as a parameter, set my id to it and  */
        /* because $id is set we will do an UPDATE. TODO: This will     */
        /* still fail if $id does not exist.                            */
             
        if ($id) {  
             $this->id = $id;
             
        /* if $id was not given try to use objects own id. If even that */
        /* does not exist the object must have been created from        */
        /* submitted data and the $id will be empty -> we must do       */
        /* an INSERT.                                                   */
             
        } else {
            $id       = $this->id;
        }          
        
        if ($id) {
            $mode  = DB_AUTOQUERY_UPDATE;
            $where = 'id=' . $this->getId();
        } else {
            $mode  = DB_AUTOQUERY_INSERT;
            $where = false;
            $this->id  = $this->dbh->nextID($this->table);
        }
             
        $var    = get_object_vars($this);

        /* This wont work prior to PHP 4.2.0 */
        $ignore = get_class_vars('DB_DataContainer');       
        foreach($ignore as $key => $val) {
            unset($var[$key]);
        }
        /* bring back id when needed */
        if ($mode == DB_AUTOQUERY_INSERT) {
            $var['id'] = $this->getId();
        }

        $table = $this->getTable();

        $result = $this->dbh->autoExecute($table, $var, $mode, $where);

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

    function setProperties($params) {

        /* TODO: PEAR errorhandling if $params != array    */
        /* TODO: add possibility to pass data as an object */
        if (is_array($params)) {

            /* use accessor methods */
            if ($this->getStrict()) {
                foreach ($params as $key => $value) {
                    $method = 'set' . $key;
                    $this->$method($value);
                }
                
            /* dont use accessor methods */
            } else {
                foreach ($params as $key => $value) {
                    $this->$key = $value;
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

  /**
    * Get the strict flag
    *
    * @return    boolean
    */  
    function getStrict() {
        return($this->strict);
    }

  /**
    * Set the database handle
    */  
    function setDbh($value) {
        $this->dbh = $value;
    }

  /**
    * Set id of the object
    */  
    function setId($value) {
        $this->id = $value;
    }

  /**
    * Set the key to be used when loading
    */  
    function setKey($value) {
        $this->key = $value;
    }

  /**
    * Set the table to be used in the database
    */  
    function setTable($value) {
        $this->table = $value;
    }

  /**
    * Set the strict flag
    */  
    function setStrict($value) {
        $this->strict = $value;
    }


  /**
    * Get array of DataContainer
    *
    * $params[classname] has the name of the class(es) function
    * has to return.
    *
    * $params[classname] has the name of the table to load data
    * from. If not given defaults to $params[classname].
    *
    * $params[order] translates to 'ORDER BY $params[order]'
    *
    * $params[where] translates to 'WHERE $params[where]'
    *
    * $params[limit] can be given one or two values. If only one 
    * value is given it will be considered as $count. If two values
    * are given they will be considered as $from, $count. Value can
    * be given as a string or an array. For example: '5,10' or
    * array(5,10). If only one value is given $from will be
    * considered as 0.
    *
    * @param	object  $dbh a PEAR database handler object.
    * @param	array   $params (table, classname, where, order, limit, query)
    * @access   static
    * @return   mixed   array of objects on success PEAR_Error on failure
    */  

    /* TODO: Classname still needs to be given as parameter       */
    /*       until there is a way for static method to determine  */
    /*       which class it belongs to. Make this more elegant.   */

    function getObjects($dbh, $params='') {

        $retval = array();

        if (!(trim($params['classname']))) {
            $retval = PEAR::raiseError('Need $params[classname]');
        } elseif (!(isset($params['table']))) {  
              /* defaults to $params[classname] */
              $params['table'] = $params['classname'];
        }

        if (!(PEAR::isError($retval))) {
        
            /* if we have an hardcoded query no need to generate one  */
            if (isset($params['query'])) {

                $result = $dbh->query($params['query']); 

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
                /* TODO: test with other drivers than pgsql, mysql and mssql  */
                if (isset($params['limit'])) {
                    if (is_array($params['limit'])) {
                        $from  = $params['limit'][0];
                        $count = $params['limit'][1];
                    } else {
                        /* split by whitespace and/or comma */
                        $temp = preg_split ('/[\s,]+/', $params['limit'], 2 );
                        if (count($temp) == 2) {
                          $from  = $temp[0];
                          $count = $temp[1];
                        } else {
                          $from  = 0;
                          $count = $temp[0];
                        }
                    }
                    $result = $dbh->limitQuery($query, $from, $count);
                } else {
                    $result = $dbh->query($query);
                }
            }

            if (DB::isError($result)) {
                $retval = $result;
            } else { 
                $retval = array();

                /* no need to call load() after fetching objects  */
                /* uses more memory but causes only one SELECT    */
                while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
                    $row['table']  = $params['table'];
                    $row['strict'] = $params['strict'];
                    $c = new $params['classname']($dbh, $row);
                    array_push($retval, $c);
                    unset($c);
                }
            }
        }
        return($retval);     
    }

  /**
    * Method overloading 
    *
    * NOTE! This will not work properly with PHP versions earlier
    * than 4.3.2-RC2 because of bugs in overload extension. 
    *
    */  

    function __call($method,$params,&$return) {

          $var     = get_object_vars($this);
          $retval = false;
          
          if (strpos($method, 'get') === 0) {
              $property =  substr($method, 3);
              if (array_key_exists($property, $var)) {
                  $return = $this->$property;
                  $retval = true;
              }
          } elseif (strpos($method, 'set') === 0) {
              $property =  substr($method, 3);
              if (array_key_exists($property, $var)) {
                  $this->$property = $params[0];
                  $return = null;
                  $retval = true;
              }
          }
           
        return($retval);  
    }

}

?>
