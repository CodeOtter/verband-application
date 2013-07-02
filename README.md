Verband
=======

Verband is Dutch for "Context". This framework allows for the dynamic assembly of its process flow via application-defined contexts.

Server Installation (Ubuntu 12.10)
----------------------------------

```
sudo apt-get update
sudo tasksel install lamp-server
sudo a2enmod php5
sudo a2enmod rewrite
sudo vim /etc/apache2/sites-available/default
sudo apt-get install memcached
sudo apt-get install php5-memcached
sudo apt-get install php-apc
```

Apache Setup
--------------------

```
sudo touch /etc/apache2/sites-available/<ProjectName>.com
echo "<VirtualHost *:80>" | sudo tee -a /etc/apache2/sites-available/<ProjectName>.com
echo "  ServerAdmin <yourEmail>@<ProjectName>.com" | sudo tee -a /etc/apache2/sites-available/<ProjectName>.com
echo "  ServerName <ProjectName>.com" | sudo tee -a /etc/apache2/sites-available/<ProjectName>.com
echo "  ServerAlias www.<ProjectName>.com" | sudo tee -a /etc/apache2/sites-available/<ProjectName>.com
echo "  DocumentRoot /var/www/<ProjectName>.com" | sudo tee -a /etc/apache2/sites-available/<ProjectName>.com
echo "</VirtualHost>" | sudo tee -a /etc/apache2/sites-available/<ProjectName>.com
sudo a2ensite <ProjectName>.com
sudo service apache2 restart
echo "127.0.0.1     <ProjectName>.com" | sudo tee -a /etc/hosts
```

Project Installation
--------------------

```
cd /var/www
curl -s http://getcomposer.org/installer | php
php composer.phar create-project --stability=dev verband/application <ProjectName>.com
mysql -u root -p -e "CREATE USER '<databaseUser>'@'localhost' IDENTIFIED BY  '<databasePassword>';GRANT USAGE ON * . * TO  '<databaseUser>'@'localhost' IDENTIFIED BY '<databasePassword>' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;CREATE DATABASE IF NOT EXISTS  \`<databaseName>\`;GRANT ALL PRIVILEGES ON  \`<databaseName>\` . * TO  '<databaseUser>'@'localhost';"
php application/Console.php orm:schema-tool:update --force
rm -fr .git
git init
git add .
git commit -m "First commit"
git remote add origin <Your Git repository>
git push -u origin master
```

Configuration
-------------

Now open the ```application/Settings/config.yml``` file and change the ```name```, ```webRoot```, and ```database``` settings.

Change the ```name``` property in ```composer.json``` to the Packagist name of your project.


Build Setup
-----------

```TODO: Setup Jenkins instructions```

Change ```ProjectName``` to the name of the new project in the following files:

__build.xml:__
```<project name="ProjectName" default="build">```

__build/phpdox.xml:__
```<project name="ProjectName" source="application" workdir="build/phpdox">```

__build/phpmd.xml:__
```<ruleset name="PetShapes"```

__application/Settings/phpunit.xml.dist:__
```<log type="coverage-html" target="../../build/coverage" title="ProjectName"```