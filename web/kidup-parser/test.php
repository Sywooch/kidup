<?php

require('semantics3-php/lib/Semantics3.php');

# Set up a client to talk to the Semantics3 API using your Semantics3 API Credentials
$key = 'SEM37CA5AB53E97EC90B99A1000CE3D2BB50';
$secret = 'MTRhZDk2YmI2ODA3ZjFhMmQ2NWJlMjI2ZDg4ZWU5NGM';

$requestor = new Semantics3_Products($key,$secret);

$requestor->products_field( "site", "autostole" );

# Run the request
$results = $requestor->get_products();

# View the results of the request
echo $results;