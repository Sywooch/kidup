<?php
require(__DIR__ . '/vendor/autoload.php');
require 'recipe/common.php';
date_default_timezone_set("Europe/Amsterdam");

$keyFile = __DIR__ . '/config/keys/keys.env';
$keys = (new \josegonzalez\Dotenv\Loader($keyFile))->parse()->toArray();

/////////////////////////////////////////////// DEPLOY API ///////////////////////////////////////////

$production = server('production', '31.187.70.130', 22)
    ->env('deploy_path', '/var/www')
    ->env('branch', 'master')
    ->user('root')
    ->stage('production');
$test = server('test', '178.62.234.114', 22)
    ->env('deploy_path', '/var/www')
    ->env('branch', 'develop')
    ->user('root')
    ->stage('staging');

if (getenv('CIRCLECI_TEST_PASSWORD') != false) {
    $production->password(getenv('CIRCLECI_PRODUCTION_PASSWORD'));
    $test->password(getenv('CIRCLECI_TEST_PASSWORD'));
} else {
    $production->identityFile('/vagrant/devops/.private/ssh/id_rsa.pub', '/vagrant/devops/.private/ssh/id_rsa');
    $test->identityFile('/vagrant/devops/.private/ssh/id_rsa.pub', '/vagrant/devops/.private/ssh/id_rsa');
}

set('shared_dirs', ['runtime', 'uploads']);
set('shared_files', ['config/keys/keys.env', 'config/keys/keys.json']);
set('writable_dirs', ['web/assets', 'uploads', 'runtime', 'web/release-assets']);

$repo_password = getenv('CIRCLECI_GIT_OAUTH') ? getenv('CIRCLECI_GIT_OAUTH') : $keys['git_repo_password'];
set('repository', 'https://simonnouwens:' . $repo_password . '@github.com/esquire900/kidup.git');
//set('repository', 'git@github.com:esquire900/kidup.git');

task('deploy:vendors', function () use ($repo_password) {
    run("cd {{release_path}} && composer config github-oauth.github.com " . $repo_password);
    run("cd {{release_path}} && composer install --verbose --prefer-dist --optimize-autoloader --no-progress --quiet");
})->desc('Installing vendors');

task('deploy:vendors', function () use ($repo_password) {
    run("cd {{release_path}} && composer config github-oauth.github.com " . $repo_password);
    run("cd {{release_path}} && composer install --verbose --prefer-dist --optimize-autoloader --no-progress --quiet");
})->desc('Installing vendors');

task('deploy:bower_folder', function () {
    run("cd {{release_path}} && [ -d ./vendor/bower-asset ] && mv ./vendor/bower-asset ./vendor/bower");
})->desc('Moving bower asset folder');

task('deploy:run_migrations', function () {
    run('php {{release_path}}/yii migrate up --interactive=0');
})->desc('Run migrations');

task('deploy:minify_assets', function () {
    run('sudo php {{release_path}}/yii asset {{release_path}}/config/assets/assets-all.php {{release_path}}/config/assets/assets-all-def.php');
    run('sudo php {{release_path}}/yii asset {{release_path}}/config/assets/assets.php {{release_path}}/config/assets/assets-prod.php');
})->desc('Minifying assets');

task('deploy:cache-cleanup', function () {
    run('php {{release_path}}/yii deploy/after-deploy');
})->desc("Cleaning cache");

task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
//    'deploy:bower_folder',
    'deploy:minify_assets',
    'deploy:run_migrations',
    'deploy:symlink',
    'deploy:cache-cleanup',
    'cleanup',
])->desc('Deploy your project');

after('deploy', 'success');