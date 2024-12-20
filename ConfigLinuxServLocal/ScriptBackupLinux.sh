#!/bin/bash

# Variables
BACKUP_DIR="/backup"  # Dossier de sauvegarde
WEB_DIR="/var/www/energie"
DB_HOST="localhost"
DB_NAME="db_smartcity_energie"
DB_USER="root"
DB_PASSWORD="HEH2/16"
DATE=$(date +"%Y-%m-%d_%H-%M-%S")

# Créer le dossier de sauvegarde s'il n'existe pas
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
echo "Toutes les sauvegardes sont terminées avec succès."