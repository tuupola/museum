<?php

/* vim: set ts=4 sw=4: */

// +----------------------------------------------------------------------+
// |                                                                      |
// | DB_Datacontainer.php A class for handling SQL stored data.           |
// | Copyright (C) 2002 Mika Tuupola                                      |
// |                                                                      |
// | This library is free software; you can redistribute it and/or        |
// | modify it under the terms of the GNU Lesser General Public           |
// | License as published by the Free Software Foundation; either         |
// | version 2.1 of the License, or (at your option) any later version.   |
// |                                                                      |
// | This library is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU    |
// | Lesser General Public License for more details.                      |
// |                                                                      |
// | You should have received a copy of the GNU Lesser General Public     |
// | License along with this library; if not, write to the Free Software  |
// | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 |
// | USA                                                                  |
// |                                                                      |
// | Contact: tuupola@appelsiini.net                                      |
// |                                                                      |
// +----------------------------------------------------------------------+

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


    function DB_DataContainer($dbh, $params) {

        $this->dbh   = $dbh;
        $this->setProperties($params);
        
    }


    function load() {

        $result = 0;

        /* if we have an id or key load up data from the database  */
        /* and discard any possible data given in $params.         */
        /* id overrides anything                                   */
        if ($this->id) {
            $this->key = 'id';
        }
//        if ($this->id || $this->key) {
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
        // does not exist the object must have been created from POST:ed
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

        $var = get_object_vars($this);

        /* TODO: find a more elegant way of doing this */
        unset($var['id']);
        unset($var['dbh']);
        unset($var['table']);
        unset($var['key']);

        foreach ($var as $key => $value) {
            $query .= "$key='$value', ";
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

    function setProperties($params) {
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $method = 'set' . $key;
                $this->$method($value);
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

        $retval = '';

        if (!(isset($params['autoload']))) {
            $params['autoload'] = false;
        }
 
        if (!(trim($params['table']))) {
            $retval = PEAR::raiseError('Need $params[table]');
        } elseif (!(trim($params['classname']))) {  
            $retval = PEAR::raiseError('Need $params[classname]');
        }

        if (!(PEAR::isError($retval))) {

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

            $result = $dbh->query($query);

            if (DB::isError($result)) {
                $retval = $result;
            } else { 
                $retval = array();

                while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
                    $row['table'] = $params['table'];
                    $c = new $params['classname']($dbh, $row);

//                    if ($params['autoload']) {
//                        $status = $c->load();
//                        if (PEAR::isError($status)) {
//                            $retval = $status;
//                            break;
//                        }
//                    }

                    array_push($retval, $c);
                    unset($c);
                }
            }
        }
        return($retval);     
    }        

}

?>
