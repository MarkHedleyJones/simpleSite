#! /bin/bash

install=`pwd`
echo "The website will be installed in the current directory ($install)."
echo ""
echo "Give the website a name (e.g. mywebsite)"
echo "ATTENTION: This is not the domain name, leave off any www. or .com"
echo ""
read -p "Enter a name for the website: " name
echo "The website will be called $name" 
echo ""
echo "When setting up a website in apache (the webserver) it is advised"
echo "to enter the email address of the websites administrator"
echo ""
read -p "Enter your email address: " email
echo ""
echo "Give the website its url in the form of domain.tld (e.g. mywebsite.co.nz)."
echo "ATTENTION: Dont start with www. (or similar), this is added automatically"
echo ""
read -p "Enter the url of the website: " url
echo "Website will be installed as www.$url"
echo ""
echo "This website works by watching a folder and displaying its contents as part of the website"
echo "You need to select this folder now (e.g. /home/mark/websiteStuff)"
echo ""
echo "ATTENTION: Use full pathnames"
read -p "Path the watch folder: " path
echo "The website will watch $path"
echo ""

echo "Linking /var/www/$name to $install..."
sudo ln -s $install /var/www/$name/
echo "Done!"
echo ""
echo "Creating apache configuration file (/etc/apache2/sites-available/$name)..."
sudo echo "<VirtualHost *:80>
    ServerAdmin $email
    ServerName www.$url
    DocumentRoot /var/www/$name/public_html/
            
    <Directory /var/www/$name/public_html>
        Options -Indexes +FollowSymLinks MultiViews
        AllowOverride None
        Order allow,deny
        allow from all
        DirectoryIndex /index.php
        FallbackResource /index.php
    </Directory>
</VirtualHost>

<VirtualHost *:80>
    ServerAdmin $email
    ServerName static.$url
    DocumentRoot /var/www/$name/static/
    
    <Directory /var/www/$name/static/>
        Options -Indexes +FollowSymLinks MultiViews
        AllowOverride None
        Order allow,deny
        allow from all
    </Directory>
</VirtualHost>" > /etc/apache2/sites-available/$name
echo "Done!"
echo ""
echo "Enabling new website configuration in Apache..."
sudo a2ensite $name
echo "Done!"
echo ""
echo "Restarting Apache with new configuration loaded..."
sudo service apache2 restart
echo "Done!"
echo ""
echo "Adding link in hosts file to allow local viewing of website (/etc/hosts)..."
sudo echo "
# This line added by installer for $name
127.0.0.1        www.$url
127.0.0.1        static.$url" >> /etc/hosts
echo "Done!"
echo ""
echo "Fetching required PHP-HTMLifier library from github..."
sudo rm -r lib/PHP-HTMLifier
git clone https://github.com/markjones112358/PHP-HTMLifier lib/PHP-HTMLifier
echo "Done!"
echo ""
echo "Linking the website to the watch folder ($path)"
echo "$path" > pathOverride.txt
echo "Done!"
echo ""
echo "Installation complete. You can view the website at http://www.$url"
