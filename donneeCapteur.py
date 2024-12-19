import mysql.connector
import random
import time
from datetime import datetime

# Configuration de la base de données
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'db_smartcity_energie'
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
    """Récupère les capteurs actifs (StateUp = 1) et leurs informations depuis la table sensor."""
    cursor = conn.cursor(dictionary=True)
    query = "SELECT IPv4, ID_SensorType FROM sensor WHERE StateUp = 1"
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

    insert_into_capteurs_energie(conn, sensors)
    insert_into_consommation_energie(conn)

if __name__ == "__main__":
    main()



# Tests unitaires du module donneeCapteur
# import unittest
# from unittest.mock import MagicMock, patch
# from datetime import datetime
# import random

# # Test pour les fonctions de donneecapteur

# class TestDonneesCapteur(unittest.TestCase):
#     @patch('mysql.connector.connect')
#     def test_connect_to_db_success(self, mock_connect):
#         mock_connection = MagicMock()
#         mock_connect.return_value = mock_connection

#         from donneeCapteur import connect_to_db
#         connection = connect_to_db()

#         self.assertEqual(connection, mock_connection)
#         mock_connect.assert_called_once()

#     @patch('mysql.connector.connect')
#     def test_connect_to_db_failure(self, mock_connect):
#         mock_connect.side_effect = Exception("Connexion échouée")

#         from donneeCapteur import connect_to_db
#         with self.assertRaises(SystemExit):
#             connect_to_db()

#     @patch('mysql.connector.connect')
#     def test_fetch_sensors(self, mock_connect):
#         mock_connection = MagicMock()
#         mock_cursor = MagicMock()

#         mock_connection.cursor.return_value = mock_cursor
#         mock_cursor.fetchall.return_value = [
#             {'IPv4': '192.168.1.1', 'ID_SensorType': 1},
#             {'IPv4': '192.168.1.2', 'ID_SensorType': 2}
#         ]
#         mock_connect.return_value = mock_connection

#         from donneeCapteur import fetch_sensors
#         sensors = fetch_sensors(mock_connection)

#         self.assertEqual(len(sensors), 2)
#         self.assertEqual(sensors[0]['IPv4'], '192.168.1.1')
#         self.assertEqual(sensors[1]['ID_SensorType'], 2)

#     @patch('mysql.connector.connect')
#     def test_insert_into_capteurs_energie(self, mock_connect):
#         mock_connection = MagicMock()
#         mock_cursor = MagicMock()

#         mock_connection.cursor.return_value = mock_cursor
#         mock_cursor.execute.return_value = None

#         mock_connect.return_value = mock_connection

#         sensors = [{'IPv4': '192.168.1.1', 'ID_SensorType': 1}]

#         from donneeCapteur import insert_into_capteurs_energie
#         insert_into_capteurs_energie(mock_connection, sensors)

#         mock_cursor.execute.assert_called_once()
#         mock_connection.commit.assert_called_once()

#     @patch('mysql.connector.connect')
#     def test_insert_into_consommation_energie(self, mock_connect):
#         mock_connection = MagicMock()
#         mock_cursor = MagicMock()

#         mock_connection.cursor.return_value = mock_cursor
#         mock_cursor.execute.return_value = None

#         mock_connect.return_value = mock_connection

#         from donneeCapteur import insert_into_consommation_energie
#         insert_into_consommation_energie(mock_connection)

#         mock_cursor.execute.assert_called_once()
#         mock_connection.commit.assert_called_once()

# if __name__ == "__main__":
#     unittest.main()
