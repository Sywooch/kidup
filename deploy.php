<?php
require(__DIR__ . '/vendor/autoload.php');
require 'recipe/common.php';
date_default_timezone_set("Europe/Amsterdam");


$keyFile = __DIR__ . '/config/keys/keys.env';
$keys = (new \josegonzalez\Dotenv\Loader($keyFile))->parse()->toArray();


// verify if keys are actually set - otherwise deployer will say it works while it doesn't

/////////////////////////////////////////////// DEPLOY API ///////////////////////////////////////////

if(getenv('CIRCLECI_TEST_PASSWORD') != false){
    server('production-primary', '31.187.70.130', 22)
        ->path('/var/www/')
        ->user('root');

    server('test', '178.62.234.114', 22)
        ->path('/var/www/')
        ->user('root', getenv('CIRCLECI_TEST_PASSWORD'));
}else{
    server('production-primary', '31.187.70.130', 22)
        ->path('/var/www/')
        ->user('root')
        ->pubKey();

    server('test', '178.62.234.114', 22)
        ->path('/var/www/')
        ->user('root')
        ->pubKey('/vagrant/devops/.private/ssh/id_rsa.pub', '/vagrant/devops/.private/ssh/id_rsa');
}

stage('development', array('test'), ['branch'=>'develop'], true);
stage('production', array('production-primary'), array('branch'=>'master'), true);

$repo_password = getenv('CIRCLECI_GIT_OAUTH') ? getenv('CIRCLECI_GIT_OAUTH') : $keys['git_repo_password'];
set('repository', 'https://simonnouwens:'.$repo_password.'@github.com/esquire900/kidup.git');

task('deploy:vendors', function () use ($repo_password) {
    $releasePath = env()->getReleasePath();
    cd($releasePath);
    run("curl -sS https://getcomposer.org/installer | php");
    run("php composer.phar config github-oauth.github.com ".$repo_password);
    run("php composer.phar install --verbose --prefer-dist --optimize-autoloader --no-progress --quiet");
})->desc('Installing vendors');


task('deploy:folder_permissions', function () {
    $releasePath = env()->getReleasePath();
    cd($releasePath);
    run("php init --env=Production --overwrite=All");
    run("[ -d ./vendor/bower-asset ] && mv ./vendor/bower-asset ./vendor/bower");
    set('shared_dirs', ['runtime', 'uploads']);
    set('shared_files', ['config/keys/keys.env']);
    set('writeable_dirs', ['web/assets', 'uploads', 'runtime']);
})->desc('Setting folder permissions');

task('deploy:update_database', function () {
    $releasePath = env()->getReleasePath();
    cd($releasePath);
    run("php yii migrate/up --interactive=0");
})->desc('Update database');

task('deploy:minify_assets', function () {
    $releasePath = env()->getReleasePath();
    cd($releasePath);
    // converts all assets to make sure everything is preparsed into assets
    run('sudo php yii asset config/assets/assets-all.php config/assets/assets-all-def.php');

    // minify the main bulk into a minified bundle
    run('sudo php yii asset config/assets/assets.php config/assets/assets-prod.php');
})->desc('Minifying assets');

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
    'deploy:minify_assets',
    'deploy:symlink',
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
