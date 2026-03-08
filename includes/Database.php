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
    private $error;               // Stocke les erreurs de connexion

    /**
     * Établit la connexion à la base de données
     * @return PDO|null Instance de connexion PDO ou null en cas d'erreur
     */
    public function getConnection() {
        $this->conn = null;
        $this->error = null;

        try {
            // Création de la chaîne de connexion DSN
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            
            $this->conn = new PDO(
                $dsn,
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
            
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            
            if(DEBUGGING) {
                // En mode debug, affiche les erreurs détaillées
                echo "Erreur de connexion base de données:<br>";
                echo "Host: " . $this->host . "<br>";
                echo "DB: " . $this->db_name . "<br>";
                echo "User: " . $this->username . "<br>";
                echo "Message: " . $this->error . "<br>";
                error_log("Database connection error: " . $this->error);
            }
        }

        return $this->conn;
    }
    
    /**
     * Retourne le dernier message d'erreur
     * @return string|null
     */
    public function getError() {
        return $this->error;
    }
}
?> 