<?php
/**
 * Classe Database
 * Gère la connexion à la base de données MySQL via PDO
 */
class Database {
    // Paramètres de connexion à la base de données
    private $host = DB_HOST;      // Hôte de la base de données
    private $db_name = DB_NAME;   // Nom de la base de données
    private $username = DB_USER;  // Nom d'utilisateur
    private $password = DB_PASSWORD; // Mot de passe
    private $port = DB_PORT;      // Port de la base de données
    private $conn;                // Instance de connexion PDO

    /**
     * Établit la connexion à la base de données
     * @return PDO|null Instance de connexion PDO ou null en cas d'erreur
     */
    public function getConnection() {
        $this->conn = null;

        try {
            // Création de la chaîne de connexion DSN
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            
            // Configuration des attributs PDO
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $e) {
            // En mode debug, afficher l'erreur
            if(DEBUGGING) {
                echo "Erreur de connexion : " . $e->getMessage();
            }
        }

        return $this->conn;
    }
}
?> 