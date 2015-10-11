<?php
require(__DIR__ . '/vendor/autoload.php');
require 'recipe/common.php';
date_default_timezone_set("Europe/Amsterdam");

$keyFile = __DIR__ . '/config/keys/keys.env';
$keys = (new \josegonzalez\Dotenv\Loader($keyFile))->parse()->toArray();

/////////////////////////////////////////////// DEPLOY API ///////////////////////////////////////////

$production = server('production', '54.93.103.33', 22)
    ->env('deploy_path', '/var/www')
    ->env('branch', 'master')
    ->user('ubuntu')
    ->stage('production');
//$productionLarge = server('production', '52.29.41.0', 22)
//    ->env('deploy_path', '/var/www')
//    ->env('branch', 'master')
//    ->user('ubuntu')
//    ->stage('production');
$test = server('test', '178.62.234.114', 22)
    ->env('deploy_path', '/var/www')
    ->env('branch', 'develop')
    ->user('root')
    ->stage('staging');

if (getenv('CIRCLECI_TEST_PASSWORD') != false) {
    $production->password(getenv('CIRCLECI_PRODUCTION_PASSWORD'));
    $test->password(getenv('CIRCLECI_TEST_PASSWORD'));
} else {
    $production->pemFile('/vagrant/devops/.private/ssh/kidup-aws.pem');
    $test->identityFile('/vagrant/devops/.private/ssh/id_rsa.pub', '/vagrant/devops/.private/ssh/id_rsa');
}

set('shared_dirs', ['runtime', 'web/release-assets/js', 'web/release-assets/css']);
set('shared_files', ['config/keys/keys.env', 'config/keys/keys.json', 'config/config-local.php']);
set('writable_dirs', ['web/assets', 'runtime', 'web/release-assets', 'web/packages']);

//$repo_password = getenv('CIRCLECI_GIT_OAUTH') ? getenv('CIRCLECI_GIT_OAUTH') : $keys['git_repo_password'];
$repo_password = 'd9c7b5bea7deb271c5d3c00376acbd18891c49cd';
set('repository', 'https://kevin91nl:' . $repo_password . '@github.com/esquire900/kidup.git');

task('deploy:vendors', function () use ($repo_password) {
    run("cd {{release_path}} && composer config github-oauth.github.com " . $repo_password);
    run("cd {{release_path}} && composer install --verbose --prefer-dist --optimize-autoloader --no-progress --quiet");
})->desc('Installing vendors');

task('deploy:run_migrations', function () {
    run('php {{release_path}}/yii migrate up --interactive=0');
})->desc('Run migrations');

task('deploy:minify_assets', function () {
    run('sudo php {{release_path}}/yii asset {{release_path}}/config/assets/assets.php {{release_path}}/config/assets/assets-prod.php');
})->desc('Minifying assets');

task('deploy:enable_ssl', function () {
    $branch = env('branch');
    // only do this for production
    if($branch == 'master'){
        run('sudo mv -f {{release_path}}/web/ssl.htaccess {{release_path}}/web/.htaccess');
    }
})->desc('Enabling ssl');

task('deploy:cache-cleanup', function () {
    run('php {{release_path}}/yii deploy/after-deploy');
})->desc("Cleaning cache");

/**
 * Cleanup old releases. Customized function as it needs a sudo for production
 */
task('cleanup', function () {
    $releases = env('releases_list');

    $keep = get('keep_releases');

    while ($keep > 0) {
        array_shift($releases);
        --$keep;
    }

    foreach ($releases as $release) {
        run("sudo rm -rf {{deploy_path}}/releases/$release");
    }

    run("cd {{deploy_path}} && if [ -e release ]; then rm release; fi");
    run("cd {{deploy_path}} && if [ -h release ]; then rm release; fi");

})->desc('Cleaning up old releases');


task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'deploy:minify_assets',
    'deploy:run_migrations',
    'deploy:enable_ssl',
    'deploy:symlink',
    'deploy:cache-cleanup',
    'cleanup',
])->desc('Deploy your project');

after('deploy', 'success');