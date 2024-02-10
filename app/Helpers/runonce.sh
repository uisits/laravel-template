#!/bin/bash

#Needed for ldaprecord SSL
mkdir -p /etc/ldap
echo "TLS_CACERT      /etc/ssl/certs/ca-certificates.crt" > /etc/ldap/ldap.conf

find /var/www/laravel/vendor/stephenjude/filament-debugger/src -name DebuggerPlugin.php -exec sed -i 's/horizon/pulse/g' {} \;
sed -i '$d' /var/www/laravel/vendor/stephenjude/filament-debugger/src/Traits/HasDebuggers.php

cat >> /var/www/laravel/vendor/stephenjude/filament-debugger/src/Traits/HasDebuggers.php << EOL

    public function pulse(): NavigationItem
    {
        return NavigationItem::make()
            ->visible(\$this->authorized(config('filament-debugger.permissions.pulse')))
            ->group(config('filament-debugger.group'))
            ->icon('heroicon-o-globe-europe-africa')
            ->url(url: url('pulse'), shouldOpenInNewTab: true)
            ->label('Pulse');
    }
}
EOL

ip_addr=$(ip addr show eth0 | awk '/inet / {print $2}' | awk -F "/" '{print $1}')
echo "$ip_addr  $DOCKER_SERVER" >> /etc/hosts

npm install
npm run build


\cp /var/www/laravel/.vimrc /root/
curl -fLo ~/.vim/autoload/plug.vim --create-dirs https://raw.githubusercontent.com/junegunn/vim-plug/master/plug.vim

vim +'PlugInstall --sync' +qa

