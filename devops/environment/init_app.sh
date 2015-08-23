cd /vagrant
composer -dmemory_limit=600M install

php yii migrate/up --interactive=0

