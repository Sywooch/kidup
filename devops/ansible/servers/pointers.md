ssh-agent /bin/sh
ssh-add /vagrant/devops/.private/ssh/id_rsa

ssh-add *.pem
ssh ...

--ask-pass parameter asks the password, and allows for password instead of keyfile login

ansible-playbook -i ansible/servers/hosts  ansible/servers.yml

# Ubuntu
Make sure to NOT be a super user (root)!
ssh-add *.pem
sudo chmod 0777 /home/vagrant/.ansible
sudo chmod 0777 /home/vagrant/.ansible -R
ansible-playbook -i ansible/test-server/hosts  ansible/beta-server.yml -vvvv


eval `ssh-agent -s`
ssh-add