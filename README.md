simpleSite
============
A simple website that generates its content based on the contents of a watched folder, think glorified Apache Directory Index page, written in PHP.

Installation
============

## Quick install - Debian / Ubuntu / Mint:

Download and extract the zip file (or clone this repository using git) into the directory you wish it to run from. 

*The installation directory does not have to be anywhere special - during setup you will be asked to provide the path to the folder that you wish to watch.*

Using a terminal, move into the unpacked folder and execute `sudo sh setup.sh` to begin the install process.

## Manual install

These instructions assume you are using a Debian based distribution. These are essentially the same steps used in the installer. 

**Important:** Replace the words in [brackets] with the specifics for your install

### 1. Setup the path to the watch folder

    cd [path to unpacked website]
    nano pathOverride.txt

Enter the path to the watched folder (e.g. /home/username/myWebsiteContent). Press Ctrl+x to exit, when prompted enter 'Y' to save and press enter again.

### 2. Set permissions for PHP on the install folder

These commands must be executed in [path to unpacked website]

    chgrp www-data public_html -R
    chmod 775 public_html -R
    
### 3. Fetch required HTML rendering library

    cd lib
    git clone https://github.com/markjones112358/PHP-HTMLifier
    
If you dont have git installed execute `sudo apt-get install git`.

### 4. Link the installation folder to default apache web location
    
    sudo ln -s [path to unpacked website] /var/www/[domain name for website]
    
### 5. Create an apache configuration file

    cd /etc/apache2/sites-available
    sudo cp default [domain name for website]
    sudo nano [domain name for website]


Change the file to match the following.

*Note:* don't include www or any subdomains in feild **domain name for website**, they are already written in where required.



    <VirtualHost *:80>
        ServerAdmin [your email address]
        ServerName www.[domain name for website]
        DocumentRoot /var/www/[domain name for website]/public_html/

        <Directory /var/www/[domain name for website]/public_html>
                Options -Indexes +FollowSymLinks MultiViews
                AllowOverride None
                Order allow,deny
                allow from all
                DirectoryIndex /index.php
                FallbackResource /index.php
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/error.log

        # Possible values include: debug, info, notice, warn, error, crit,
        # alert, emerg.
        LogLevel warn

        CustomLog ${APACHE_LOG_DIR}/access.log combined
    </VirtualHost>


    <VirtualHost *:80>
        ServerAdmin [your email address]
        ServerName static.[domain name for website]
        DocumentRoot /var/www/[domain name for website]/static/

        <Directory /var/www/[domain name for website]/static>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride None
                Order allow,deny
                allow from all
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/error.log

        # Possible values include: debug, info, notice, warn, error, crit,
        # alert, emerg.
        LogLevel warn

        CustomLog ${APACHE_LOG_DIR}/access.log combined
    </VirtualHost>


### 6. Enable new website in Apache and reload Apache

    sudo a2ensite [domain name for website]
    sudo service apache2 reload

### 7. Link website url to local machine (for local viewing)

    sudo nano /etc/hosts
    
and add the followinig line

    127.0.0.1       www.[domain name for website]
    127.0.0.1       static.[domain name for website]
    
