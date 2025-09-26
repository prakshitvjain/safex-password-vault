#!/bin/bash
sudo systemctl start httpd
sudo systemctl start mariadb
x=$(ip -4 addr show scope global | grep inet | awk '{print $2}' | cut -d/ -f1)
echo "The services are successfully started, open any browser and type $x/login.php"
