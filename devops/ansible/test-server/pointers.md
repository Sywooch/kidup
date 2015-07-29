ssh-keygen -t rsa -b 4096 -C "simonnouwens@gmail.com"

ssh-copy-id root@178.62.234.114

ansible-playbook -i ansible/test-server/hosts  ansible/beta-server.yml
