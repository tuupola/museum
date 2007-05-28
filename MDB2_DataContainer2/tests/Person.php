<?php

if (@include(dirname(__FILE__)."/../MDB2/DataContainer2.php")) {
    $status = '';
} else if (@include('MDB2/DataContainer2.php')) {
    $status = '';
} else {
    $status = 'skip';
}

class person extends MDB2_DataContainer2 {

    var $first_name;
    var $last_name;
    var $mobile;

    /* NOTE: You can leave the constructor away from sibling */
    /* if all default values are used                        */
/*   
    function Person($dbh, $params) {
        $this->DB_DataContainer($dbh, $params);    
    }
*/

    function createDB() {
        $this->dbh->loadModule('Manager');
        $this->dbh->query("DROP TABLE $this->table ");
        $this->dbh->manager->dropSequence($this->table);
        $this->dbh->query(
            "CREATE TABLE $this->table (
                 id INTEGER PRIMARY KEY NOT NULL,
                 first_name VARCHAR(32),
                 last_name VARCHAR(32),
                 mobile VARCHAR(16),
                 nosuch VARCHAR(16)
             )"
        );

        $params['strict']    = false;     

        $params['id']        = '';     
        $params['first_name'] = 'Mika';     
        $params['last_name']  = 'Tuupola';     
        $params['mobile']    = '+358-50-123456';     

        $p = new Person($this->dbh, $params);
        $p->save();

        $params['id']        = '';     
        $params['first_name'] = 'Janne';     
        $params['last_name']  = 'Kjellman';     
        $params['mobile']    = '+358-40-123456';     

        $p->setProperties($params);
        $p->save();

        $params['id']        = '';     
        $params['first_name'] = 'Juha-Matti';     
        $params['last_name']  = 'Tuupola';     
        $params['mobile']    = '+358-41-123456';     

        $p->setProperties($params);
        $p->save();

        $params['id']        = '';     
        $params['first_name'] = 'Jenni';     
        $params['last_name']  = 'Laine';     
        $params['mobile']    = '+358-9-123456';     

        $p->setProperties($params);
        $p->save();

        $this->dbh->query(
            "UPDATE $this->table SET nosuch='should not load'"
        );

    }
}

