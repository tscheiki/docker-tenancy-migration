#!/bin/bash

#----------------------------------------------
## a new docker-pod will be generated
## ./newClient.sh 001 c1.r2o
## ./newClient.sh [PortID] [Domain]
## @author Roland Bole
#----------------------------------------------

BASE_PATH=/home/rbole/core/app
PORT_MYSQL=7$1
PORT=8$1
PORT_PHP=9$1
DOMAIN=$2

echo "a new client docker-pod will be generated"

#ServerConfig
sed -e"s;%PORT%;$PORT;g" -e"s;%DOMAIN%;$DOMAIN;g" $BASE_PATH/template/host_server_block.tpl > /etc/nginx/sites-available/$DOMAIN
ln -s /etc/nginx/sites-available/$DOMAIN /etc/nginx/sites-enabled/$DOMAIN

#DockerPodConfig
mkdir $BASE_PATH/c$1
sed -e"s;%PORT%;$PORT;g" -e"s;%PORT_PHP%;$PORT_PHP;g" -e"s;%PORT_MYSQL%;$PORT_MYSQL;g" $BASE_PATH/template/docker-compose.tpl > $BASE_PATH/c$1/docker-compose.yml
cp $BASE_PATH/template/site.tpl $BASE_PATH/c$1/site.conf

#Reload http-proxy
service nginx reload

#Start docker-pod
docker-compose -f $BASE_PATH/c$1/docker-compose.yml up -d
