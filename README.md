# stakeholder-panel


## How to deploy

1. Create a instance of Ubuntu 18.04
2. Connect via SSH to the server you created, in root user

- First of all, create a non-root user with sudo privilegies
```
sudo adduser <username>

sudo usermod -aG sudo <username>
```

- Login with the user you created
```
su <username>
```

- Then, update the system
```
sudo apt-get update
```

### Installing the tools

- Install NGINX
```
sudo apt install nginx
```

- Install PHP 7.2-fpm
```
sudo apt install php-fpm
```
- Check if the php-fpm service is active
```
systemctl status php7.2-fpm
```
- The result should look like this
```
php7.2-fpm.service: The PHP 7.2 FastCGI Process Manager

Loaded: loaded (/lib/systemd/system/php7.2-fpm.service; enabled; vendor preset: enabled)

Active: active (running) since Fri 2019-03-22 19:24:51 CST; 12s ago

Docs: man:php-fpm7.2(8)

Main PID: 1087 (php-fpm7.2)

Status: "Processes active: 0, idle: 2, Requests: 0, slow: 0, Traffic: 0req/sec"
Tasks: 3 (limit: 1115)

CGroup: /system.slice/php7.2-fpm.service

├─1087 php-fpm: master process (/etc/php/7.2/fpm/php-fpm.conf)

├─1090 php-fpm: pool www

└─1091 php-fpm: pool www
```

- Installing composer
- First, you need to install some php dependencies
```
sudo apt install curl php-cli php-mbstring php-curl php-mysql php-bcmath php-zip git unzip
```
- Then, install composer
```
sudo apt install composer
```

- To check if composer is installed, run
```
composer
```
- The result should look like this
```
/ ____/___  ____ ___  ____  ____  ________  _____

/ /  / __ \/ __ `__ \/ __ \/ __ \/ ___/ _ \/ ___/

/ /___/ /_/ / / / / / / /_/ / /_/ (__ )  __/ /

\____/\____/_/ /_/ /_/ .___/\____/____/\___/_/

/_/

Composer version 1.8.4 2019-02-11 10:52:10
```

### MySQl
- You have two options to install MySql

1. Use a database service, separated from the machine, like Amazon RDS (recommended)
2. Or install MySql in the server

- To install MySql, run the following command
```
sudo apt install mysql-server
```

- Then, check with
```
mysql -u root -p
```
- If, don't work, try this
```
mysql
```
It depends how you installed, with or not a password, etc.

- The terminal should change to MySql prompt
```
mysql>
```

- This step is very important, with that credentials we access the database in symfony application.
- Now, you create a database to the app
```
mysql> CREATE DATABASE <database_name>;

Query OK, 1 row affected (0.00 sec)
```
- And create a user and grant privileges to that user.
```
mysql> CREATE USER '<user>'@'localhost' IDENTIFIED BY '<password>';

Query OK, 1 row affected (0.00 sec)

mysql> GRANT ALL PRIVILEGES ON <database_name>.* TO '<user>'@'localhost';

Query OK, 0 rows affected (0.00 sec)
```
- To confirm if the database was created, run
```
mysql> SHOW DATABASES;

+--------------------+
| Database  |
+--------------------+
| information_schema |
| <database_name>  |
| mysql  |
| performance_schema |
| sys  |
+--------------------+

5 rows in set (0.00 sec)
```
- To exit MySql terminal
```
mysql> quit
```