library
=======

A Symfony project created on March 27, 2015, 1:28 pm.

```bash
#!/bin/bash
 
# to see a more detailed progress do:
# tail -f install.log
 
######## CONFIG ##########
 
router="index.php"
serverRoot="/server"
domain="example.org"
timezone="America/Mazatlan"
 
##########################
 
echo -e "\n\n\n" > install.log
printf "\nroae.me installation initiated\n\n"
printf "Installing repos                  "
rpm -Uvh http://yum.postgresql.org/9.3/redhat/rhel-6-x86_64/pgdg-centos93-9.3-1.noarch.rpm >> install.log 2>>install.log
rpm -Uvh http://download.fedoraproject.org/pub/epel/6/i386/epel-release-6-8.noarch.rpm >> install.log 2>>install.log
rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-6.rpm >> install.log 2>>install.log
#nginx repo
echo "
[nginx]
name=nginx repo
baseurl=http://nginx.org/packages/centos/\$releasever/\$basearch/
gpgcheck=0
enabled=1" > /etc/yum.repos.d/nginx.repo
printf "[DONE]\n"
printf "Installing rsync                  "
yum install rsync -y >> install.log 2>>install.log
printf "[DONE]\n"
printf "Installing nginx                  "
yum install nginx -y >> install.log 2>>install.log
printf "[DONE]\n"
printf "Installing redis                  "
yum install redis -y >> install.log 2>>install.log
printf "[DONE]\n"
printf "Installing php                    "
yum install php-fpm php-pecl-xdebug php-pgsql php-bcmath php-xml php-opcache -y >> install.log 2>>install.log
printf "[DONE]\n"
printf "Installing postgreSQL 9.3         "
yum install postgresql93-server postgresql93-devel -y >> install.log 2>>install.log
printf "[DONE]\n"
printf "Initializing postgreSQL           "
su - postgres -c /usr/pgsql-9.3/bin/initdb >> install.log 2>>install.log
printf "[DONE]\n"
printf "Configuring php                   "
sed -i "s@;date.timezone =@date.timezone = $timezone@g" /etc/php.ini
printf "[DONE]\n"
printf "Configuring nginx                 "
mkdir $serverRoot
echo "
<?php
echo '<h1>Server configured successfully!</h1>';
" > $serverRoot/$router 
rm -f /etc/nginx/conf.d/*
echo "
server {
    listen       80;
    server_name  l localhost $domain www.$domain;

    location / {
        root $serverRoot;
        index $router;
        try_files \$uri \$uri/ /$router;
    }

    location ~ \.php$ {
        root $serverRoot;
        fastcgi_index   $router;
        fastcgi_pass    127.0.0.1:9000;
        include         fastcgi_params;
        fastcgi_param   SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
    }
}
" > /etc/nginx/conf.d/server.conf
printf "[DONE]\n"
printf "Configuring firewall              "
iptables -F >> install.log 2>>install.log
iptables -A INPUT -p tcp --tcp-flags ALL NONE -j DROP >> install.log 2>>install.log
iptables -A INPUT -p tcp ! --syn -m state --state NEW -j DROP >> install.log 2>>install.log
iptables -A INPUT -p tcp --tcp-flags ALL ALL -j DROP >> install.log 2>>install.log
iptables -A INPUT -i lo -j ACCEPT >> install.log 2>>install.log
iptables -A INPUT -p tcp -m tcp --dport 22    -j ACCEPT >> install.log 2>>install.log
iptables -A INPUT -p tcp -m tcp --dport 25    -j ACCEPT >> install.log 2>>install.log
iptables -A INPUT -p tcp -m tcp --dport 80    -j ACCEPT >> install.log 2>>install.log
iptables -A INPUT -p tcp -m tcp --dport 81    -j ACCEPT >> install.log 2>>install.log
iptables -A INPUT -p tcp -m tcp --dport 82    -j ACCEPT >> install.log 2>>install.log
iptables -A INPUT -p tcp -m tcp --dport 110   -j ACCEPT >> install.log 2>>install.log
iptables -A INPUT -p tcp -m tcp --dport 143   -j ACCEPT >> install.log 2>>install.log
iptables -A INPUT -p tcp -m tcp --dport 465   -j ACCEPT >> install.log 2>>install.log
iptables -A INPUT -p tcp -m tcp --dport 587   -j ACCEPT >> install.log 2>>install.log
iptables -A INPUT -p tcp -m tcp --dport 993   -j ACCEPT >> install.log 2>>install.log
iptables -A INPUT -p tcp -m tcp --dport 995   -j ACCEPT >> install.log 2>>install.log
iptables -A INPUT -p tcp -m tcp --dport 2525  -j ACCEPT >> install.log 2>>install.log
iptables -A INPUT -p tcp -m tcp --dport 5432  -j ACCEPT >> install.log 2>>install.log
iptables-save | sudo tee /etc/sysconfig/iptables >> install.log 2>>install.log
service iptables restart >> install.log 2>>install.log
printf "[DONE]\n"
printf "Setting programs to start at boot "
chkconfig --levels 235 php-fpm on
chkconfig --levels 235 redis on
chkconfig --levels 235 postgresql-9.3 on
chkconfig --levels 235 nginx on
printf "[DONE]\n"
printf "Starting services                 "
service php-fpm start >> install.log 2>>install.log
service redis start >> install.log 2>>install.log
service postgresql-9.3 start >> install.log 2>>install.log
service nginx start >> install.log 2>>install.log
printf "[DONE]\n"
echo "ALL DONE!"
```
