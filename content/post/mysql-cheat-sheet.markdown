---
title: "MySQL Cheat Sheet"
slug: "mysql-cheat-sheet"
date: "2006-05-18T19:15:00-06:00"
author: "fak3r"
categories:
- linux
tags:
- howto
---

Here’s a page that’s just too useful not to mirror, some cat named Neal Parikh has a page he calls a [MySQL Cheat Sheet](http://nparikh.freeshell.org/unix/mysql.php); click that link for the latest revision, or read more for my mirrored copy for reference.  When I have to do DB work outside of what I usually do day-to-day I hit Google for some tutorials, so having this as a reference will be helpful in the future.

**Selecting a database:**




    
    <code>mysql> USE database db_name;</code>





**Listing databases:**




    
    <code>mysql> SHOW DATABASES;</code>





**Listing tables in a db:**




    
    <code>mysql> SHOW TABLES;</code>





**Describing the format of a table:**




    
    <code>mysql> DESCRIBE table;</code>





**Creating a database:**




    
    <code>mysql> CREATE DATABASE db_name;</code>





**Creating a table:**




    
    <code>mysql> CREATE TABLE table_name (field1_name TYPE(SIZE), field2_name TYPE(SIZE));</code>





Ex: mysql> CREATE TABLE pet (name VARCHAR(20), sex CHAR(1), birth DATE);

**Load tab-delimited data into a table:**




    
    <code>mysql> LOAD DATA LOCAL INFILE "infile.txt" INTO TABLE table_name;</code>





(Use \n for NULL)

**Inserting one row at a time:**




    
    <code>mysql> INSERT INTO table_name VALUES ('MyName', 'MyOwner', '2002-08-31');</code>





(Use NULL for NULL)

**Retrieving information (general):**




    
    <code>mysql> SELECT from_columns FROM table WHERE conditions;
    All values: SELECT * FROM table;
    Some values: SELECT * FROM table WHERE rec_name = "value";
    Multiple critera: SELECT * FROM TABLE WHERE rec1 = "value1" AND rec2 = "value2";</code>





**Reloading a new data set into existing table:**




    
    <code>mysql> SET AUTOCOMMIT=1; # used for quick recreation of table
    mysql> DELETE FROM pet;
    mysql> LOAD DATA LOCAL INFILE "infile.txt" INTO TABLE table;</code>





**Fixing just one record:**




    
    <code>mysql> UPDATE table SET value = "new_value" WHERE record_name = "value";</code>





**Selecting specific columns:**




    
    <code>mysql> SELECT column_name FROM table;</code>





**Retrieving unique output records:**




    
    <code>mysql> SELECT DISTINCT column_name FROM table;</code>





**Sorting:**




    
    <code>mysql> SELECT col1, col2 FROM table ORDER BY col2;
    Backwards: SELECT col1, col2 FROM table ORDER BY col2 DESC;</code>





**Date calculations:**




    
    <code>mysql> SELECT CURRENT_DATE, (YEAR(CURRENT_DATE)-YEAR(date_col)) AS time_diff [FROM table];
    MONTH(some_date) extracts the month value and DAYOFMONTH() extracts day.</code>





**Pattern Matching:**




    
    <code>mysql> SELECT * FROM table WHERE rec LIKE "blah%";</code>





(% is wildcard - arbitrary # of chars)
Find 5-char values: SELECT * FROM table WHERE rec like ”**_**”;
(_ is any single character)

**Extended Regular Expression Matching:**




    
    <code>mysql> SELECT * FROM table WHERE rec RLIKE "^b$";</code>





(. for char, […] for char class, * for 0 or more instances
^ for beginning, {n} for repeat n times, and $ for end)
(RLIKE or REGEXP)
To force case-sensitivity, use “REGEXP BINARY”

**Counting Rows:**




    
    <code>mysql> SELECT COUNT(*) FROM table;</code>





**Grouping with Counting:**




    
    <code>mysql> SELECT owner, COUNT(*) FROM table GROUP BY owner;</code>





(GROUP BY groups together all records for each ‘owner’)

**Selecting from multiple tables:**




    
    <code>mysql> SELECT pet.name, comment FROM pet, event WHERE pet.name = event.name;</code>





(You can join a table to itself to compare by using ‘AS’)

**Currently selected database:**




    
    <code>mysql> SELECT DATABASE();</code>





**Maximum value:**




    
    <code>mysql> SELECT MAX(col_name) AS label FROM table;</code>





**Auto-incrementing rows:**




    
    <code>mysql> CREATE TABLE table (number INT NOT NULL AUTO_INCREMENT, name CHAR(10) NOT NULL);
    mysql> INSERT INTO table (name) VALUES ("tom"),("dick"),("harry");</code>





**Adding a column to an already-created table:**




    
    <code>mysql> ALTER TABLE tbl ADD COLUMN [column_create syntax] AFTER col_name;</code>





**Removing a column:**




    
    <code>mysql> ALTER TABLE tbl DROP COLUMN col;</code>





(Full [ALTER TABLE](http://www.mysql.com/doc/en/ALTER_TABLE.html) syntax available at mysql.com.)

**Batch mode (feeding in a script):**




    
    <code># mysql -u user -p < batch_file</code>





(Use -t for nice table layout and -vvv for command echoing.)
Alternatively:




    
    <code>mysql> source batch_file;</code>



