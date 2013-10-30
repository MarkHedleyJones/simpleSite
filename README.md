website-base
============

Basic website suited for personal use - requires no database; based on PHP and a custom HTML rendering library.

Installation
============

These instructions assume you are using Debian or Ubuntu.

    cd /etc/apache2/sites-available
    sudo cp default website-base
    sudo nano website-base

Then make sure the file matches the following. You can replace www.simpleWebsite.co.nz with whatever your site is called.


    <VirtualHost *:80>
        ServerAdmin me@myEmailProvider.com
        ServerName www.simpleWebsite.co.nz
        DocumentRoot /var/www/website-base/public_html/

        <Directory /var/www/website-base/public_html>
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


    <VirtualHost *:80>
        ServerAdmin me@myEmailProvider.com
        ServerName static.simpleWebsite.co.nz
        DocumentRoot /var/www/website-base/static/

        <Directory /var/www/website-base/static>
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
