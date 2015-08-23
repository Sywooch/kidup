ssh-agent /bin/sh
ssh-add /vagrant/devops/.private/ssh/id_rsa

ansible-playbook -i ansible/test-server/hosts  ansible/beta-server.yml

# Ubuntu
Make sure to NOT be a super user (root)!
ssh-add *.pem
sudo chmod 0777 /home/vagrant/.ansible
sudo chmod 0777 /home/vagrant/.ansible -R
ansible-playbook -i ansible/test-server/hosts  ansible/beta-server.yml -vvvv
