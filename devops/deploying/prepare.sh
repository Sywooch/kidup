#!/bin/bash
rm /vagrant/web/release-assets/js/*
rm /vagrant/web/release-assets/css/*
sudo php /vagrant/yii asset /vagrant/config/assets/assets-all.php /vagrant/config/assets/assets-all-def.php
sudo php /vagrant/yii asset /vagrant/config/assets/assets.php /vagrant/config/assets/assets-prod.php