# $Id: mysql.sql,v 1.3 2003/09/05 15:54:29 tuupola Exp $

#
# LOAD DATA INFILE '/tmp/ip-to-country.txt' INTO TABLE ip2country 
# FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n'; 
#

CREATE TABLE ip2country (
    ipfrom  INTEGER UNSIGNED,
    ipto    INTEGER UNSIGNED,
    two     CHAR(2),
    three   CHAR(3),
    country VARCHAR(64)
);
