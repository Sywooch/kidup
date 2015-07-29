<?php

echo json_encode([
    'staging' => ['root@'.file_get_contents('/vagrant/devops/ansible/test-server/ip.txt')]
]);

?>