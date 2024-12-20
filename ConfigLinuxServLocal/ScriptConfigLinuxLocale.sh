# Mise à jour du système
echo "Mise à jour du système..."
sudo dnf update -y


# Installation et configuration d'Apache
echo "Installation et configuration d'Apache..."
sudo dnf install httpd -y
sudo systemctl start httpd
sudo systemctl enable httpd
sudo sed -i 's/^Listen 80/Listen 0.0.0.0:80/' /etc/httpd/conf/httpd.conf
sudo sed -i 's/^Listen 443/Listen 0.0.0.0:443/' /etc/httpd/conf/httpd.conf
sudo systemctl restart httpd
echo "<?php phpinfo(); ?>" | sudo tee /var/www/html/info.php


# Installation et configuration de MariaDB

echo "Installation et configuration de MariaDB..."
sudo dnf install mariadb-server mariadb -y
sudo systemctl start mariadb
sudo systemctl enable mariadb
sudo mysql_secure_installation

# Création d'un utilisateur admin avec des privilèges
sudo mysql -u root -p -e "
CREATE USER 'admin'@'localhost' IDENTIFIED BY 'MotDePasseAdmin';
GRANT ALL PRIVILEGES ON *.* TO 'admin'@'localhost';
FLUSH PRIVILEGES;"


# Installation et configuration de PhpMyAdmin
echo "Installation et configuration de PhpMyAdmin..."
sudo dnf install phpmyadmin -y
sudo sed -i 's/Require local/Require all granted/' /etc/httpd/conf.d/phpMyAdmin.conf
sudo systemctl restart httpd


# Configuration du pare-feu
echo "Configuration du pare-feu..."
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --permanent --add-service=mysql
sudo firewall-cmd --reload
