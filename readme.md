# Kidup repository

## Install dev environment

Vagrant can be used to install a developer environment which is the same for all developers. Simply install vagrant from https://www.vagrantup.com, and run "vagrant up" in the terminal in the root folder of the kidup repo. After a couple of minutes, a linux installation with all the necessary programs has been set up and should be ready for development. To complete the framework setup:

- SSH into vagrant (run "vagrant ssh"), which gets you inside the linux OS
- go to the vagrant folder (run "cd /vagrant")
- run "php composer.phar global require "fxp/composer-asset-plugin:1.0.0" "
- install all application dependencies (run "php composer.phar install")
- Initialize the database (run "php yii migrate/up"), select yes

You can now use the url 192.168.33.99 (for example http://192.168.33.99/web/ for the main site) for development.
