#!/bin/bash
source ~/.bashrc
export PATH=$PATH:/vagrant/vendor/codeception/codeception
source ~/.bashrc
sudo apt-get install -y default-jre phantomjs
sudo chmod 0777 /vagrant/tests
sudo chmod 0777 /vagrant/tests -R