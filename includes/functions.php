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

function webpPath($imageSrc) {
    return preg_replace('/\.(png|jpe?g)$/i', '.webp', $imageSrc);
}

function pictureTag($src, $alt, $attrs = '') {
    $webp = webpPath($src);
    $type = preg_match('/\.png$/i', $src) ? 'image/png' : 'image/jpeg';
    return '<picture>'
        . '<source srcset="' . sanitizeOutput($webp) . '" type="image/webp">'
        . '<img src="' . sanitizeOutput($src) . '" alt="' . sanitizeOutput($alt) . '" ' . $attrs . '>'
        . '</picture>';
}

/**
 * Formate une date au format Y-m-d
 * @param string $date Date à formater
 * @return string Date formatée
 */
function formatDate($date) {
    $months = ['', 'Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
    $timestamp = strtotime($date);
    $month = (int)date('n', $timestamp);
    $year = date('Y', $timestamp);
    return $months[$month] . ' ' . $year;
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
 * Récupère les commentaires d'un projet avec likes et structure thread
 * @param PDO $db Instance de connexion PDO
 * @param string $project_url URL du projet
 * @param int|null $current_user_id ID utilisateur courant (pour user_liked)
 * @return array Liste des commentaires threadés
 */
function getProjectComments($db, $project_url, $current_user_id = null) {
    try {
        $query = "SELECT c.*, u.username,
                 (SELECT COUNT(*) FROM comment_likes cl WHERE cl.comment_id = c.id) AS likes_count
                 FROM comments c 
                 JOIN users u ON c.user_id = u.id 
                 WHERE c.project_url = :project_url 
                 ORDER BY c.created_at ASC";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':project_url', $project_url);
        $stmt->execute();
        $all = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check user likes
        $userLikes = [];
        if ($current_user_id) {
            $lq = $db->prepare("SELECT comment_id FROM comment_likes WHERE user_id = :uid");
            $lq->execute([':uid' => $current_user_id]);
            $userLikes = array_column($lq->fetchAll(PDO::FETCH_ASSOC), 'comment_id');
        }

        foreach ($all as &$c) {
            $c['user_liked'] = in_array($c['id'], $userLikes);
        }

        // Build thread tree
        $tree = [];
        $map = [];
        foreach ($all as $c) {
            $c['replies'] = [];
            $map[$c['id']] = $c;
        }
        foreach ($map as $id => $c) {
            if ($c['parent_id'] && isset($map[$c['parent_id']])) {
                $map[$c['parent_id']]['replies'][] = &$map[$id];
            } else {
                $tree[] = &$map[$id];
            }
        }

        // Sort root comments newest first
        usort($tree, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return $tree;
    } catch(PDOException $e) {
        if(DEBUGGING) {
            echo "Erreur : " . $e->getMessage();
        }
        return [];
    }
}

/**
 * Compte le total des commentaires d'un projet
 */
function countProjectComments($db, $project_url) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM comments WHERE project_url = :url");
    $stmt->execute([':url' => $project_url]);
    return (int)$stmt->fetchColumn();
}

/**
 * Ajoute un commentaire à un projet (avec support parent_id pour les réponses)
 */
function addProjectComment($db, $project_url, $user_id, $content, $parent_id = null) {
    try {
        $query = "INSERT INTO comments (project_url, parent_id, user_id, content) 
                 VALUES (:project_url, :parent_id, :user_id, :content)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':project_url', $project_url);
        $stmt->bindParam(':parent_id', $parent_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':content', $content);
        return $stmt->execute();
    } catch(PDOException $e) {
        if(DEBUGGING) {
            echo "Erreur : " . $e->getMessage();
        }
        return false;
    }
}

/**
 * Récupère les commentaires d'un article de blog avec likes et structure thread
 */
function getBlogComments($db, $blog_slug, $current_user_id = null) {
    try {
        $query = "SELECT c.*, u.username,
                 (SELECT COUNT(*) FROM comment_likes cl WHERE cl.comment_id = c.id) AS likes_count
                 FROM comments c 
                 JOIN users u ON c.user_id = u.id 
                 WHERE c.blog_slug = :blog_slug 
                 ORDER BY c.created_at ASC";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':blog_slug', $blog_slug);
        $stmt->execute();
        $all = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $userLikes = [];
        if ($current_user_id) {
            $lq = $db->prepare("SELECT comment_id FROM comment_likes WHERE user_id = :uid");
            $lq->execute([':uid' => $current_user_id]);
            $userLikes = array_column($lq->fetchAll(PDO::FETCH_ASSOC), 'comment_id');
        }

        foreach ($all as &$c) {
            $c['user_liked'] = in_array($c['id'], $userLikes);
        }

        $tree = [];
        $map = [];
        foreach ($all as $c) {
            $c['replies'] = [];
            $map[$c['id']] = $c;
        }
        foreach ($map as $id => $c) {
            if ($c['parent_id'] && isset($map[$c['parent_id']])) {
                $map[$c['parent_id']]['replies'][] = &$map[$id];
            } else {
                $tree[] = &$map[$id];
            }
        }

        usort($tree, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return $tree;
    } catch(PDOException $e) {
        if(DEBUGGING) {
            echo "Erreur : " . $e->getMessage();
        }
        return [];
    }
}

/**
 * Compte le total des commentaires d'un article de blog
 */
function countBlogComments($db, $blog_slug) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM comments WHERE blog_slug = :slug");
    $stmt->execute([':slug' => $blog_slug]);
    return (int)$stmt->fetchColumn();
}

/**
 * Ajoute un commentaire à un article de blog
 */
function addBlogComment($db, $blog_slug, $user_id, $content, $parent_id = null) {
    try {
        $query = "INSERT INTO comments (blog_slug, parent_id, user_id, content) 
                 VALUES (:blog_slug, :parent_id, :user_id, :content)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':blog_slug', $blog_slug);
        $stmt->bindParam(':parent_id', $parent_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':content', $content);
        return $stmt->execute();
    } catch(PDOException $e) {
        if(DEBUGGING) {
            echo "Erreur : " . $e->getMessage();
        }
        return false;
    }
}

/**
 * Récupère les articles de blog publiés
 * @param PDO $db Instance de connexion PDO
 * @param int $limit Nombre maximum d'articles
 * @return array Liste des articles
 */
function getBlogPosts($db, $limit = 6) {
    try {
        $query = "SELECT * FROM blog_posts WHERE is_published = 1 ORDER BY created_at DESC LIMIT :limit";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
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
 * Récupère un article de blog par son slug
 * @param PDO $db Instance de connexion PDO
 * @param string $slug Slug de l'article
 * @return array|null Données de l'article ou null
 */
function getBlogPost($db, $slug) {
    try {
        $query = "SELECT * FROM blog_posts WHERE slug = :slug AND is_published = 1 LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':slug', $slug);
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
 * Edit a comment (only by its author, within content limits)
 */
function editComment($db, $comment_id, $user_id, $content) {
    $stmt = $db->prepare("UPDATE comments SET content = :content, updated_at = NOW() WHERE id = :id AND user_id = :uid");
    return $stmt->execute([':content' => $content, ':id' => $comment_id, ':uid' => $user_id]);
}

/**
 * Toggle like on a comment
 * @return array ['liked' => bool, 'count' => int]
 */
function toggleCommentLike($db, $comment_id, $user_id) {
    // Check if already liked
    $check = $db->prepare("SELECT id FROM comment_likes WHERE comment_id = :cid AND user_id = :uid");
    $check->execute([':cid' => $comment_id, ':uid' => $user_id]);
    
    if ($check->fetch()) {
        $db->prepare("DELETE FROM comment_likes WHERE comment_id = :cid AND user_id = :uid")
           ->execute([':cid' => $comment_id, ':uid' => $user_id]);
        $liked = false;
    } else {
        $db->prepare("INSERT INTO comment_likes (comment_id, user_id) VALUES (:cid, :uid)")
           ->execute([':cid' => $comment_id, ':uid' => $user_id]);
        $liked = true;
    }
    
    $count = $db->prepare("SELECT COUNT(*) FROM comment_likes WHERE comment_id = :cid");
    $count->execute([':cid' => $comment_id]);
    
    return ['liked' => $liked, 'count' => (int)$count->fetchColumn()];
}

/**
 * Récupère les stats (commentaires + likes) de tous les projets en une seule requête
 * @return array ['project_url' => ['comments' => int, 'likes' => int]]
 */
function getAllProjectStats($db) {
    $stats = [];
    try {
        $query = "SELECT c.project_url,
                         COUNT(DISTINCT c.id) AS comments,
                         COUNT(DISTINCT cl.id) AS likes
                  FROM comments c
                  LEFT JOIN comment_likes cl ON cl.comment_id = c.id
                  WHERE c.project_url IS NOT NULL
                  GROUP BY c.project_url";
        $stmt = $db->prepare($query);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $stats[$row['project_url']] = [
                'comments' => (int)$row['comments'],
                'likes' => (int)$row['likes']
            ];
        }
    } catch(PDOException $e) {}
    return $stats;
}

/**
 * Récupère les stats (commentaires + likes) de tous les articles de blog en une seule requête
 * @return array ['slug' => ['comments' => int, 'likes' => int]]
 */
function getAllBlogStats($db) {
    $stats = [];
    try {
        $query = "SELECT c.blog_slug,
                         COUNT(DISTINCT c.id) AS comments,
                         COUNT(DISTINCT cl.id) AS likes
                  FROM comments c
                  LEFT JOIN comment_likes cl ON cl.comment_id = c.id
                  WHERE c.blog_slug IS NOT NULL
                  GROUP BY c.blog_slug";
        $stmt = $db->prepare($query);
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $stats[$row['blog_slug']] = [
                'comments' => (int)$row['comments'],
                'likes' => (int)$row['likes']
            ];
        }
    } catch(PDOException $e) {}
    return $stats;
}
?> 