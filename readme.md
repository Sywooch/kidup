# Kidup repository

## Install dev environment

Vagrant can be used to install a developer environment which is the same for all developers. Simply install vagrant from https://www.vagrantup.com, and open up a terminal. Go to the {kidup}/devops folder (cd {location_of_repo}/devops) and run "vagrant up". 

After a couple of minutes (little more if its the first time), a linux installation with all the necessary programs has been set up and should be ready for development. To complete the framework setup:

- SSH into vagrant (run "vagrant ssh"), which gets you inside the linux OS
- run sudo /vagrant/devops/environment/init.sh (this might take a while - ~15-20 minutes)

You can now use the url 192.168.33.99 (for example http://192.168.33.99/web/ for the main site) for development.

## PHPStorm tricks

- Set the /vendor dir to be excluded (settings -> project settings -> directories -> mark as excluded), will sign reduce indexing time on startup.