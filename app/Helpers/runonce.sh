#!/bin/bash

ip_addr=$(ip addr show eth0 | awk '/inet / {print $2}' | awk -F "/" '{print $1}')
echo "$ip_addr  $DOCKER_SERVER" >> /etc/hosts

npm install
npm run build

\cp /var/www/laravel/.vimrc /root/
curl -fLo ~/.vim/autoload/plug.vim --create-dirs https://raw.githubusercontent.com/junegunn/vim-plug/master/plug.vim

vim +'PlugInstall --sync' +qa

sed -i "s/create another/add another/g" /var/www/laravel/vendor/filament/actions/resources/lang/en/create.php

