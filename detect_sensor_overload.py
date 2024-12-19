import mysql.connector
from datetime import datetime, timedelta

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

def fetch_last_week_production(conn):
    """Récupère la production des capteurs pour les 7 derniers jours."""
    cursor = conn.cursor(dictionary=True)
    end_date = datetime.now()
    start_date = end_date - timedelta(days=7)

    query = (
        "SELECT id_capteur, SUM(valeur) AS total_production "
        "FROM capteurs_energie "
        "WHERE date_mesure BETWEEN %s AND %s "
        "GROUP BY id_capteur"
    )
    cursor.execute(query, (start_date, end_date))
    results = cursor.fetchall()
    cursor.close()
    return results

def fetch_previous_average_production(conn, id_capteur, start_date):
    """Récupère la production moyenne avant une période donnée."""
    cursor = conn.cursor(dictionary=True)
    query = (
        "SELECT AVG(valeur) AS avg_production "
        "FROM capteurs_energie "
        "WHERE id_capteur = %s AND date_mesure < %s"
    )
    cursor.execute(query, (id_capteur, start_date))
    result = cursor.fetchone()
    cursor.close()
    return result['avg_production'] if result['avg_production'] else 0

def has_recent_alert(conn, id_capteur):
    """Vérifie si une alerte a été envoyée pour un capteur dans les dernières 24 heures."""
    cursor = conn.cursor(dictionary=True)
    check_date = datetime.now() - timedelta(hours=24)

    query = (
        "SELECT COUNT(*) AS recent_alerts "
        "FROM alertes_surcharge "
        "WHERE id_capteur = %s AND date_signalement > %s"
    )
    cursor.execute(query, (id_capteur, check_date))
    result = cursor.fetchone()
    cursor.close()
    return result['recent_alerts'] > 0

def insert_alert(conn, id_capteur, description, niveau):
    """Insère une alerte dans la table alertes_surcharge."""
    cursor = conn.cursor()
    query = (
        "INSERT INTO alertes_surcharge (id_capteur, description, niveau, date_signalement) "
        "VALUES (%s, %s, %s, %s)"
    )
    data = (id_capteur, description, niveau, datetime.now())
    try:
        cursor.execute(query, data)
        conn.commit()
        print(f"Alerte insérée: {data}")
    except mysql.connector.Error as err:
        print(f"Erreur lors de l'insertion de l'alerte: {err}")
    finally:
        cursor.close()

def check_production_anomaly():
    """Vérifie les anomalies de production et insère des alertes si nécessaire."""
    conn = connect_to_db()
    try:
        last_week_data = fetch_last_week_production(conn)

        for entry in last_week_data:
            id_capteur = entry['id_capteur']
            total_production = entry['total_production']
            start_date = datetime.now() - timedelta(days=7)

            previous_avg = fetch_previous_average_production(conn, id_capteur, start_date)

            if previous_avg > 0 and total_production > 2 * previous_avg:
                if not has_recent_alert(conn, id_capteur):
                    niveau = 'critique' if total_production > 3 * previous_avg else 'moyen'
                    description = (
                        f"Surcharge détectée: production {total_production} kWh, "
                        f"soit +{round((total_production - previous_avg) / previous_avg * 100)}% par rapport à la moyenne."
                    )
                    insert_alert(conn, id_capteur, description, niveau)
                else:
                    print(f"Alerte récente déjà présente pour le capteur {id_capteur}, aucune nouvelle alerte envoyée.")
    finally:
        conn.close()

if __name__ == "__main__":
    check_production_anomaly()