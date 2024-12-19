import mysql.connector
import random
import time
from datetime import datetime

# Configuration de la base de données
db_config = {
    'host': 'x',
    'user': 'y',
    'password': 'z',
    'database': 'a'
}

def connect_to_db():
    """Connecte à la base de données et retourne la connexion."""
    try:
        conn = mysql.connector.connect(**db_config)
        return conn
    except mysql.connector.Error as err:
        print(f"Erreur de connexion: {err}")
        exit(1)

def fetch_sensors(conn):
    """Récupère les capteurs et leurs informations depuis la table sensor."""
    cursor = conn.cursor(dictionary=True)
    query = "SELECT IPv4, ID_SensorType FROM sensor"
    try:
        cursor.execute(query)
        sensors = cursor.fetchall()
        return sensors
    except mysql.connector.Error as err:
        print(f"Erreur lors de la récupération des capteurs: {err}")
        return []
    finally:
        cursor.close()

def insert_into_capteurs_energie(conn, sensors):
    """Insère une entrée aléatoire dans la table capteurs_energie."""
    if not sensors:
        print("Aucun capteur disponible pour l'insertion.")
        return

    cursor = conn.cursor()
    sensor = random.choice(sensors)
    ip = sensor['IPv4']
    type_energie = 'solaire' if sensor['ID_SensorType'] == 1 else 'éolienne'
    valeur = round(random.uniform(1, 50), 2)
    date_mesure = datetime.now().strftime('%Y-%m-%d %H:%M:%S')

    query = (
        "INSERT INTO capteurs_energie (id_capteur, type_energie, valeur, date_mesure) "
        "VALUES (%s, %s, %s, %s)"
    )
    data = (ip, type_energie, valeur, date_mesure)

    try:
        cursor.execute(query, data)
        conn.commit()
        print(f"Donnée insérée dans capteurs_energie: {data}")
    except mysql.connector.Error as err:
        print(f"Erreur lors de l'insertion dans capteurs_energie: {err}")
    finally:
        cursor.close()

def insert_into_consommation_energie(conn):
    """Insère une entrée aléatoire dans la table consommation_energie."""
    cursor = conn.cursor()
    id_adresse = random.randint(1, 100)  # Générer un id_adresse aléatoire
    consommation = round(random.uniform(1, 5), 2)
    date = datetime.now().strftime('%Y-%m-%d %H:%M:%S')

    query = (
        "INSERT INTO consommation_energie (id_adresse, consommation, date) "
        "VALUES (%s, %s, %s)"
    )
    data = (id_adresse, consommation, date)

    try:
        cursor.execute(query, data)
        conn.commit()
        print(f"Donnée insérée dans consommation_energie: {data}")
    except mysql.connector.Error as err:
        print(f"Erreur lors de l'insertion dans consommation_energie: {err}")
    finally:
        cursor.close()

def main():
    conn = connect_to_db()
    sensors = fetch_sensors(conn)

    try:
        while True:
            insert_into_capteurs_energie(conn, sensors)
            insert_into_consommation_energie(conn)
            time.sleep(300)
    except KeyboardInterrupt:
        print("Arrêt du script par l'utilisateur.")
    finally:
        conn.close()

if __name__ == "__main__":
    main()
