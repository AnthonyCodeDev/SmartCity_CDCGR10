#!/bin/bash


# Variables de sauvegarde
BACKUP_DIR="/backup"  # Dossier de sauvegarde
WEB_DIR="/var/www/energie"
DB_HOST="localhost"
DB_NAME="db_smartcity_energie"
DB_USER="root"
DB_PASSWORD="HEH2/16"
DATE=$(date +"%Y-%m-%d_%H-%M-%S")


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


# Sauvegarde
echo "Création du dossier de sauvegarde..."
mkdir -p "$BACKUP_DIR"

# Sauvegarde du dossier web
echo "Sauvegarde du dossier $WEB_DIR..."
tar -czf "$BACKUP_DIR/web_backup_$DATE.tar.gz" -C "$(dirname "$WEB_DIR")" "$(basename "$WEB_DIR")"
if [ $? -eq 0 ]; then
    echo "Sauvegarde du dossier web terminée : $BACKUP_DIR/web_backup_$DATE.tar.gz"
else
    echo "Erreur lors de la sauvegarde du dossier web." >&2
    exit 1
fi

# Sauvegarde de la base de données
echo "Sauvegarde de la base de données $DB_NAME..."
mysqldump -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" > "$BACKUP_DIR/db_backup_$DATE.sql"
if [ $? -eq 0 ]; then
    echo "Sauvegarde de la base de données terminée : $BACKUP_DIR/db_backup_$DATE.sql"
else
    echo "Erreur lors de la sauvegarde de la base de données." >&2
    exit 1
fi

# Résumé
echo "Toutes les configurations et sauvegardes sont terminées avec succès."
