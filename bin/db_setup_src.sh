#!/bin/bash

mysql -u root -P [[db_port]] -proot -e \
"create database test_my_app;
grant all privileges on test_my_app.* to 'my_app'@'%';
grant all privileges on test_my_app.* to 'root'@'%';
flush privileges;"
