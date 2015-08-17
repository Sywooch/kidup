<?php
// API configuration
$api = [];
$api['base'] = 'http://keys.kidup.dk/';
$api['get-keys'] = $api['base'] . 'index.php';

// setup the result data that will be given back eventually
$result = [];
$result['trace'] = [];
$result['warning'] = [];
// check whether keys.env already exist
if (file_exists(__DIR__.'/keys.env')) {
    $newName = 'keys_' . time() . '_backup.env';
    $result['warning'][] = 'keys.env already exists and will be moved to ' . $newName;
    rename('keys.env', $newName);
}

// check whether keys.json is available
if (!file_exists(__DIR__.'/keys.json')) {
    echo json_encode([
        'error' => 'keys.json does not exist.'
    ]);
    exit;
}

// check the configuration
$config = file_get_contents(__DIR__.'/keys.json');
$config = json_decode($config, true);
if (!isset($config['type'])) {
    $result['error'] = '"type" parameter was not set in keys.json.';
    echo json_encode($result);
    exit;
}
if (!isset($config['key'])) {
    $result['error'] = '"key" parameter was not set in keys.json.';
    echo json_encode($result);
    exit;
}
$type = $config['type'];
$key = $config['key'];
$api['get-keys'] .= '?type=' . $type;

// fetch the keys
$result['trace'][] = 'Fetching keys.env...';
$url = $api['get-keys'];
$encrypted_keys = file_get_contents($url);

// they are encoded, so we need to decode them
$result['trace'][] = 'Decoding keys...';
$keys = openssl_decrypt($encrypted_keys, 'aes128', $key);

// save them
$result['trace'][] = 'Save keys to keys.env...';
$fh = fopen(__DIR__.'/keys.env', 'w');
fwrite($fh, $keys);
fclose($fh);

// give back the result
echo json_encode($result);
exit;
?>