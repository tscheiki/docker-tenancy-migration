---
version: '3'
services:
 web:
  image: nginx:alpine
  ports:
   - "%PORT%:80"
  volumes:
   - ./site.conf:/etc/nginx/conf.d/default.conf
   - /home/rbole/core/app/src:/usr/share/nginx/html:ro
  links:
   - php
 php:
  image: php7:latest
  ports:
   - "%PORT_PHP%:9000"
  volumes:
   - /home/rbole/core/app/src:/usr/share/nginx/html
 db:
  image: mysql:latest
  volumes:
   - data-volume-db:/var/lib/mysql
   - /home/rbole/core/app/import:/docker-entrypoint-initdb.d
  restart: always
  environment:
   MYSQL_ROOT_PASSWORD: 1234567
 phpmyadmin:
  image: phpmyadmin/phpmyadmin:latest
  links:
   - db
  ports:
   - "%PORT_MYSQL%:80"
  environment:
   MYSQL_USERNAME: root
   MYSQL_ROOT_PASSWORD: 1234567
volumes:
 data-volume-db: