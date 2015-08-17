ssh-keygen -t rsa -b 4096 -C "simonnouwens@gmail.com"

ssh-copy-id root@188.166.122.88

ansible-playbook -i ansible/test-server/hosts  ansible/beta-server.yml

# Ubuntu
Make sure to NOT be a super user (root)!
ssh-add *.pem
sudo chmod 0777 /home/vagrant/.ansible
sudo chmod 0777 /home/vagrant/.ansible -R
ansible-playbook -i ansible/test-server/hosts  ansible/beta-server.yml -vvvv
