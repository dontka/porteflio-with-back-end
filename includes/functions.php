<?php
/**
 * Récupère l'URL système configurée
 * @return string URL système ou chaîne vide si non définie
 */
function getSystemUrl() {
    return defined('SYS_URL') ? SYS_URL : '';
}

/**
 * Vérifie si le mode debug est activé
 * @return bool True si le mode debug est activé
 */
function isDebugMode() {
    return defined('DEBUGGING') && DEBUGGING;
}

/**
 * Récupère la locale par défaut
 * @return string Code de la locale par défaut
 */
function getDefaultLocale() {
    return defined('DEFAULT_LOCALE') ? DEFAULT_LOCALE : 'en_fr';
}

/**
 * Nettoie le texte pour l'affichage HTML
 * @param string $text Texte à nettoyer
 * @return string Texte nettoyé
 */
function sanitizeOutput($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Formate une date au format Y-m-d
 * @param string $date Date à formater
 * @return string Date formatée
 */
function formatDate($date) {
    return date('Y-m-d', strtotime($date));
}

/**
 * Récupère les données du profil
 * @param PDO $db Instance de connexion PDO
 * @return array|null Données du profil ou null en cas d'erreur
 */
function getProfileData($db) {
    try {
        $query = "SELECT * FROM profile LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        if(DEBUGGING) {
            echo "Erreur : " . $e->getMessage();
        }
        return null;
    }
}

/**
 * Récupère la liste des projets
 * @param PDO $db Instance de connexion PDO
 * @return array Liste des projets
 */
function getProjects($db) {
    try {
        $query = "SELECT * FROM projects ORDER BY created_at DESC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        if(DEBUGGING) {
            echo "Erreur : " . $e->getMessage();
        }
        return [];
    }
}

/**
 * Récupère la liste des compétences
 * @param PDO $db Instance de connexion PDO
 * @return array Liste des compétences
 */
function getSkills($db) {
    try {
        $query = "SELECT * FROM skills ORDER BY level DESC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        if(DEBUGGING) {
            echo "Erreur : " . $e->getMessage();
        }
        return [];
    }
}

/**
 * Récupère la liste des expériences
 * @param PDO $db Instance de connexion PDO
 * @return array Liste des expériences
 */
function getExperience($db) {
    try {
        $query = "SELECT * FROM experience ORDER BY start_date DESC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        if(DEBUGGING) {
            echo "Erreur : " . $e->getMessage();
        }
        return [];
    }
}

/**
 * Récupère les détails d'un projet spécifique
 * @param PDO $db Instance de connexion PDO
 * @param string $project_url URL du projet
 * @return array|null Données du projet ou null si non trouvé
 */
function getProjectDetails($db, $project_url) {
    try {
        $query = "SELECT * FROM projects WHERE project_url = :project_url LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':project_url', $project_url);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        if(DEBUGGING) {
            echo "Erreur : " . $e->getMessage();
        }
        return null;
    }
}

/**
 * Vérifie si l'utilisateur est connecté
 * @return bool True si l'utilisateur est connecté
 */
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Récupère les commentaires d'un projet
 * @param PDO $db Instance de connexion PDO
 * @param string $project_url URL du projet
 * @return array Liste des commentaires
 */
function getProjectComments($db, $project_url) {
    try {
        $query = "SELECT c.*, u.username 
                 FROM comments c 
                 JOIN users u ON c.user_id = u.id 
                 WHERE c.project_url = :project_url 
                 ORDER BY c.created_at DESC";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':project_url', $project_url);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        if(DEBUGGING) {
            echo "Erreur : " . $e->getMessage();
        }
        return [];
    }
}

/**
 * Ajoute un commentaire à un projet
 * @param PDO $db Instance de connexion PDO
 * @param string $project_url URL du projet
 * @param int $user_id ID de l'utilisateur
 * @param string $content Contenu du commentaire
 * @return bool True si le commentaire a été ajouté
 */
function addProjectComment($db, $project_url, $user_id, $content) {
    try {
        $query = "INSERT INTO comments (project_url, user_id, content) 
                 VALUES (:project_url, :user_id, :content)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':project_url', $project_url);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':content', $content);
        return $stmt->execute();
    } catch(PDOException $e) {
        if(DEBUGGING) {
            echo "Erreur : " . $e->getMessage();
        }
        return false;
    }
}
?> 