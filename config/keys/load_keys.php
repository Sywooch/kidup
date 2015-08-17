<?php
$keyFile = __DIR__ . '/keys.env';
if (!file_exists($keyFile)) {
    echo 'php '.__DIR__.'/load_keyfile.php';
    exec('php '.__DIR__.'/load_keyfile.php');
}
$keys = (new \josegonzalez\Dotenv\Loader($keyFile))->parse()->toArray();