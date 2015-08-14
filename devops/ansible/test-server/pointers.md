ssh-keygen -t rsa -b 4096 -C "simonnouwens@gmail.com"

ssh-copy-id root@188.166.122.88

ansible-playbook -i ansible/test-server/hosts  ansible/beta-server.yml
