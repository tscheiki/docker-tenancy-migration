#!/bin/bash

#----------------------------------------------
## deletes all generated customer containers
## ./clear.sh 001 c1.r2o
## ./clear.sh [PortID] [Domain]
## @author Roland Bole
#----------------------------------------------

docker-compose -f /home/rbole/core/app/c$1/docker-compose.yml down

rm -R /home/rbole/core/app/c$1
rm /etc/nginx/sites-available/$2 
rm /etc/nginx/sites-enabled/$2 

docker volume rm c$1_data-volume-db

