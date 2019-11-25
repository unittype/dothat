<img src="https://avatars1.githubusercontent.com/u/57694591?s=460&v=4" align="left" height="230" width="230" >

# dothat@KU (COSE451)

### Team Members: 
- Shon 
- Yun
- Joey
- John

-----

## Requirement
- Nginx
- PHP
- Postgresql

## Operating Systems
- Linux Ubuntu (recommended)

## Installitions

* type in terminal

```sh
$ sudo apt-get update
```

----
# Installing Nginx
```sh
$ sudo apt-get install nginx-full
```
* commands

```sh
$ sudo service nginx start | restart | reload | stop | status
```
#### Configuration
* dot to the configuration file and paste the code below
* file could be find on path ```/etc/nginx/site-available/default ```
* don't forget make a path of folder ```root /path/to/the/clone/folder/of/dothat;```

```sh
limit_req_zone $binary_remote_addr zone=one:10m rate=30r/m;


server {
	listen 80 default_server;
	listen [::]:80 default_server;

	# SSL configuration
	listen 443 ssl default_server;
	listen [::]:443 ssl default_server;	
	include snippets/snakeoil.conf;

	root /path/to/the/clone/folder/of/dothat;
	
	index index.php index.html index.htm index.nginx-debian.html;

	server_name _;

	location / {
		try_files $uri $uri/ =404;
	}

	location ~ \.php$ {
		include snippets/fastcgi-php.conf;
		#fastcgi_pass 127.0.0.1:9000;
		fastcgi_pass unix:/run/php/php7.0-fpm.sock;
	}

	location ~ /\.ht {
		deny all;
	}
}
```
* reload and restart nginx server configurations with :

```sh
$ sudo service nginx reload
$ sudo service nginx restart
```


---- 
# Installing PHP
```sh
$ sudo apt-get install php7.0-fpm
```
* or 

```sh
$ sudo apt-get install php
```

----
# Installing Postgresql
```sh
$ sudo apt-get install postgresql postgresql-contrib
```
* Usage of postgresql guides are given [here](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-postgresql-on-ubuntu-16-04)

* Once you had installed DB postgres, sign in via command line interface and create a database called ```dothat```. 
```sh
$ psql -U postgres
Password for user postgres: 
psql (9.5.19)
Type "help" for help.
```

* To create database
```sh
CREATE DATABASE dothat;
```
```sh
postgres=# \c dothat
You are now connected to database "dothat" as user "postgres".
dothat=# 
```
* To make a database scheme just import using below code:
```sh
\i /path/to/dothat/SQL_Querry.sql
```
This will makes easy connection with web application.

----

# Connection : dothat &rightarrow; postgreSQL

- Change database password configuration in project ```doatht/load.php ```. where ```'your_db_password'``` Open file ```.php``` with any text editor.
```php
// definition of DB connection
define(DB_HOST, 'localhost');
define(DB_PORT, '5432');
define(DB_PASSWD, 'your_db_password');
define(DB_USER, 'postgres');
define(DB_NAME, 'dothat');
```

-----

# Usage
* Open your browser and type ```localhost``` or ```127.0.0.0.1``` 

-----

+ ( don't take it personal ) if there are some confusing parts please just ignoree and try to make attack others' project. Good luck.