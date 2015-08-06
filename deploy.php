<?php
require(__DIR__ . '/vendor/autoload.php');
require 'recipe/common.php';
date_default_timezone_set("Europe/Amsterdam");


$keyFile = __DIR__ . '/config/keys.env';
$keys = (new \josegonzalez\Dotenv\Loader($keyFile))->parse()->toArray();


// verify if keys are actually set - otherwise deployer will say it works while it doesn't
if(empty($keys['main_server_password'])){
    echo "Deployment keys not set, check keys.env"; exit();
}
/////////////////////////////////////////////// DEPLOY API ///////////////////////////////////////////

server('production-primary', '31.187.70.130', 22)
    ->path('/var/www/')
    ->user('root', $keys['main_server_password']);

server('test', '178.62.234.114', 22)

    ->path('/var/www/')
    ->user('root', $keys['test_server_password']);

stage('development', array('test'), ['branch'=>'develop'], true);
stage('production', array('production-primary'), array('branch'=>'develop'), true);

set('repository', 'https://'.$keys['git_repo_username'].':'.$keys['git_repo_password'].'@github.com/esquire900/kidup.git');

task('deploy:vendors', function () {
    global $keys;
    $releasePath = env()->getReleasePath();
    cd($releasePath);
    run("curl -sS https://getcomposer.org/installer | php");
    run("php composer.phar config github-oauth.github.com ".$keys['github_oauth']);
    run("php composer.phar install --verbose --prefer-dist --optimize-autoloader --no-progress --quiet");
})->desc('Installing vendors');


task('deploy:folder_permissions', function () {
    $releasePath = env()->getReleasePath();
    cd($releasePath);
    run("php init --env=Production --overwrite=All");
    run("[ -d ./vendor/bower-asset ] && mv ./vendor/bower-asset ./vendor/bower");
    set('shared_dirs', ['runtime', 'uploads']);
    set('shared_files', ['config/keys.env']);
    set('writeable_dirs', ['web/assets', 'uploads', 'runtime']);
})->desc('Setting folder permissions');

task('deploy:update_database', function () {
    $releasePath = env()->getReleasePath();
    cd($releasePath);
    run("php yii migrate/up --interactive=0");
})->desc('Update database');

task('deploy:replace_index', function () {
    $releasePath = env()->getReleasePath()."/web";
    cd($releasePath);
    run('mv -f index-production.php index.php');
})->desc('Update database');

task('deploy', [
    'deploy:start',
    'deploy:prepare',
    'deploy:update_code',
    'deploy:vendors',
    'deploy:folder_permissions',
    'deploy:shared',
    'deploy:symlink',
    'deploy:update_database',
    'deploy:writeable_dirs',
    'deploy:replace_index',
    'cleanup',
    'deploy:end'
])->desc('Deploy your project');

/**
 * Success message
 */
after('deploy', function () {
    $host = config()->getHost();
    writeln("<info>KidUp Deployed @</info> <fg=cyan>$host</fg=cyan>");
});
