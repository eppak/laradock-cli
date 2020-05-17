# Laradock Client

Installation
----
$ wget https://github.com/eppak/laradock-cli/raw/master/builds/laradock-cli
$ chmod +x laradock-cli
$ sudo cp laradock-cli /usr/bin/laradock-cli

Upgrade
----
$ sudo laradock-cli self-update

Configuration
You can create a laradock.yml in .laradock-cli folder in your home or in currend directory.
Use this content and customize as you wish:

name: my-docker
php: 7.4
mysql: 5.7

Usage
----

$ laradock-cli init
Inizialize a new laradock folder in the current directory, you can add --path=/your/path to change destination.

$ laradock-cli start
Start the laradock containers, you can add --path=/your/path to change destination.

$ laradock-cli stop
Stop the laradock containers, you can add --path=/your/path to change destination.

$ laradock-cli check
Check if istances started correctly.

$ laradock-cli workspace
Enter in the bash of the workspace container where you can run artisan.

$ laradock-cli mysql
Enter in the bash of the mysql container.

