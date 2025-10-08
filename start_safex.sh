#!/bin/bash
cat << "EOF"
 ____         __     __  __ __     __          _ _   
/ ___|  __ _ / _| ___\ \/ / \ \   / /_ _ _   _| | |_ 
\___ \ / _` | |_ / _ \\  /   \ \ / / _` | | | | | __|
 ___) | (_| |  _|  __//  \    \ V / (_| | |_| | | |_ 
|____/ \__,_|_|  \___/_/\_\    \_/ \__,_|\__,_|_|\__|
                                                     
EOF
sleep 1
if [ "$(id -u)" -ne 0 ]; then
  echo "This command requires root privileges, kindly run as root or with sudo"
  exit 1
else
  echo "Starting services..."
  sleep 1
  sudo systemctl start httpd
  sudo systemctl start mariadb
  x=$(ip -4 addr show scope global | grep inet | awk '{print $2}' | cut -d/ -f1)
  echo "Services successfully started"
  echo "The project is accessible at URL http://$x/login.php"
fi




