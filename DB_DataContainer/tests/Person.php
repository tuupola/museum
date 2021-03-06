<?php

if (@include(dirname(__FILE__)."/../DataContainer.php")) {
    $status = '';
} else if (@include('DB/DataContainer.php')) {
    $status = '';
} else {
    $status = 'skip';
}

class person extends DB_DataContainer {

    var $firstname;
    var $lastname;
    var $mobile;

    /* NOTE: You can leave the constructor away from sibling */
    /* if all default values are used                        */
/*   
    function Person($dbh, $params) {
        $this->DB_DataContainer($dbh, $params);    
    }
*/

    function createDB() {
        $this->dbh->query("DROP TABLE $this->table ");
        $this->dbh->dropSequence($this->table);
        $this->dbh->query(
            "CREATE TABLE $this->table (
                 id INTEGER PRIMARY KEY NOT NULL,
                 firstname VARCHAR(32),
                 lastname VARCHAR(32),
                 mobile VARCHAR(16),
                 nosuch VARCHAR(16)
             )"
        );

        $params['strict']    = false;     

        $params['id']        = '';     
        $params['firstname'] = 'Mika';     
        $params['lastname']  = 'Tuupola';     
        $params['mobile']    = '+358-50-123456';     

        $p = new Person($this->dbh, $params);
        $p->save();

        $params['id']        = '';     
        $params['firstname'] = 'Janne';     
        $params['lastname']  = 'Kjellman';     
        $params['mobile']    = '+358-40-123456';     

        $p->setProperties($params);
        $p->save();

        $params['id']        = '';     
        $params['firstname'] = 'Juha-Matti';     
        $params['lastname']  = 'Tuupola';     
        $params['mobile']    = '+358-41-123456';     

        $p->setProperties($params);
        $p->save();

        $params['id']        = '';     
        $params['firstname'] = 'Jenni';     
        $params['lastname']  = 'Laine';     
        $params['mobile']    = '+358-9-123456';     

        $p->setProperties($params);
        $p->save();

        $this->dbh->query(
            "UPDATE $this->table SET nosuch='should not load'"
        );

    }
}

?>

