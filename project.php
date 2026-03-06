<?php
session_start();
require_once 'config.php';
require_once 'includes/Database.php';
require_once 'includes/functions.php';


// Initialisation de la connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Récupération des données
$profile = getProfileData($db);
$project_url = isset($_GET['url']) ? $_GET['url'] : '';
$project = getProjectDetails($db, $project_url);

// Si le projet n'existe pas, rediriger vers la page d'accueil
if (!$project) {
    header('Location: index.php');
    exit;
}

// Vérification du mode debug
$debugMode = isDebugMode();
$systemUrl = getSystemUrl();
$locale = getDefaultLocale();
?>
<!DOCTYPE html>
<html lang="<?php echo substr($locale, 0, 2); ?>">
<head>
    <title><?php echo $debugMode ? '[DEBUG] ' : ''; ?><?php echo sanitizeOutput($project['title']); ?> - Donatien KANANE</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo sanitizeOutput($project['description']); ?>">
    <meta name="author" content="<?php echo sanitizeOutput($profile['name'] ?? 'Donatien KANANE'); ?>">    
    <link rel="shortcut icon" href="favicon.ico">  
    
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500&display=swap' rel='stylesheet' type='text/css'> 
    
    <!-- FontAwesome JS -->
    <script defer src="<?php echo $systemUrl; ?>assets/fontawesome/js/all.js"></script>
    
    <!-- Global CSS -->
    <link rel="stylesheet" href="<?php echo $systemUrl; ?>assets/plugins/bootstrap/css/bootstrap.min.css">  
    
    <!-- Theme CSS -->  
    <link id="theme-style" rel="stylesheet" href="<?php echo $systemUrl; ?>assets/css/styles.css">
</head> 

<body>
    <!-- ===== NAVIGATION ===== -->
    <nav class="navbar-top scrolled" id="navbar">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="index.php" class="nav-brand">DK<span>.</span></a>
            <div class="nav-links d-none d-md-flex">
                <a href="index.php#projects">Projets</a>
                <a href="index.php#skills">Compétences</a>
                <a href="index.php#experience">Expérience</a>
                <a href="index.php#contact">Contact</a>
            </div>
            <div class="nav-actions d-flex align-items-center gap-3">
                <div class="form-check form-switch mb-0">
                    <input type="checkbox" class="form-check-input" id="darkSwitch" />
                    <label class="form-check-label" for="darkSwitch"><i class="fas fa-moon"></i></label>
                </div>
                <a class="btn btn-primary-custom btn-sm" href="index.php"><i class="fas fa-arrow-left"></i> Retour</a>
            </div>
        </div>
    </nav>

    <!-- ===== PROJECT HERO ===== -->
    <section class="project-hero">
        <div class="project-hero-bg">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
        </div>
        <div class="container">
            <div class="project-hero-content">
                <a href="index.php#projects" class="project-hero-back"><i class="fas fa-arrow-left"></i> Tous les projets</a>
                <h1><?php echo sanitizeOutput($project['title']); ?></h1>
                <div class="project-hero-meta">
                    <?php if ($project['is_featured']): ?>
                    <span class="project-meta-badge featured"><i class="fas fa-star"></i> Projet phare</span>
                    <?php endif; ?>
                    <span class="project-meta-badge"><i class="fas fa-calendar"></i> <?php echo formatDate($project['created_at']); ?></span>
                </div>
            </div>
        </div>
    </section>
    
    <div class="container project-main-wrapper">
        <div class="row g-4">
            <!-- Main content -->
            <div class="col-lg-8">
                <div class="project-detail-card">
                    <?php if (!empty($project['image_url'])): ?>
                    <div class="project-detail-image">
                        <img class="img-fluid" src="<?php echo sanitizeOutput($project['image_url']); ?>" alt="<?php echo sanitizeOutput($project['title']); ?>" />
                    </div>
                    <?php endif; ?>
                    <div class="project-detail-body">
                        <h2 class="project-detail-heading">Description du projet</h2>
                        <div class="project-detail-text">
                            <?php echo nl2br(sanitizeOutput($project['description'])); ?>
                        </div>
                        <?php if (!empty($project['project_url'])): ?>
                        <div class="project-detail-actions">
                            <a href="<?php echo sanitizeOutput($project['project_url']); ?>" class="btn btn-primary-custom" target="_blank">
                                <i class="fas fa-external-link-alt"></i> Voir le projet en ligne
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="project-comments-section">
                    <div class="project-comments-header">
                        <h3><i class="fas fa-comments"></i> Commentaires</h3>
                        <span class="comment-count-badge" id="commentCount">0</span>
                    </div>
                    
                    <?php if(!isUserLoggedIn()): ?>
                        <div class="comment-login-prompt">
                            <div class="comment-login-icon"><i class="fas fa-user-circle"></i></div>
                            <h5>Connectez-vous pour commenter</h5>
                            <p>Rejoignez la discussion et partagez votre avis sur ce projet</p>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="btn btn-primary-custom">
                                    <i class="fas fa-sign-in-alt"></i> Se connecter
                                </a>
                                <a href="register.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="btn btn-outline-primary-custom">
                                    <i class="fas fa-user-plus"></i> S'inscrire
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="comment-form-card">
                            <div class="d-flex align-items-center mb-3 gap-2">
                                <div class="comment-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Utilisateur'); ?></h6>
                                    <small class="text-muted">Connecté</small>
                                </div>
                            </div>
                            <form id="commentForm">
                                <div class="mb-3">
                                    <textarea class="form-control" id="commentContent" rows="3" 
                                              placeholder="Partagez votre avis sur ce projet..." required></textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted"><i class="fas fa-info-circle"></i> Visible par tous les visiteurs</small>
                                        <small class="text-muted"><span id="charCount">0</span>/500</small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="fas fa-paper-plane"></i> Publier
                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>

                    <div id="commentsList">
                        <?php
                        $comments = getProjectComments($db, $project['project_url']);
                        if (empty($comments)): ?>
                            <div class="comment-empty-state">
                                <i class="fas fa-comments"></i>
                                <h5>Aucun commentaire pour le moment</h5>
                                <p>Soyez le premier à partager votre avis sur ce projet !</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($comments as $comment): ?>
                                <div class="comment-item-card" data-comment-id="<?php echo $comment['id']; ?>">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="comment-avatar me-2">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0"><?php echo htmlspecialchars($comment['username']); ?></h6>
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> 
                                                <?php echo date('d/m/Y H:i', strtotime($comment['created_at'])); ?>
                                            </small>
                                        </div>
                                        <?php if (isUserLoggedIn() && $_SESSION['user_id'] == $comment['user_id']): ?>
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <button class="dropdown-item text-danger delete-comment" 
                                                            data-comment-id="<?php echo $comment['id']; ?>">
                                                        <i class="fas fa-trash"></i> Supprimer
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="comment-content">
                                        <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="project-sidebar-card">
                    <h4><i class="fas fa-info-circle"></i> Informations</h4>
                    <div class="sidebar-info-list">
                        <div class="sidebar-info-item">
                            <span class="sidebar-info-label"><i class="fas fa-user"></i> Auteur</span>
                            <span class="sidebar-info-value"><?php echo sanitizeOutput($profile['name'] ?? 'Donatien KANANE'); ?></span>
                        </div>
                        <div class="sidebar-info-item">
                            <span class="sidebar-info-label"><i class="fas fa-calendar"></i> Date</span>
                            <span class="sidebar-info-value"><?php echo formatDate($project['created_at']); ?></span>
                        </div>
                        <?php if ($project['is_featured']): ?>
                        <div class="sidebar-info-item">
                            <span class="sidebar-info-label"><i class="fas fa-star"></i> Statut</span>
                            <span class="sidebar-info-value featured-text">Projet phare</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($project['project_url'])): ?>
                    <a href="<?php echo sanitizeOutput($project['project_url']); ?>" class="btn btn-primary-custom w-100 mt-3" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Visiter le projet
                    </a>
                    <?php endif; ?>
                </div>

                <div class="project-sidebar-card">
                    <h4><i class="fas fa-share-alt"></i> Partager</h4>
                    <div class="sidebar-share-links">
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode($systemUrl . 'project.php?url=' . urlencode($project['project_url'])); ?>" target="_blank" class="share-btn linkedin" title="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($systemUrl . 'project.php?url=' . urlencode($project['project_url'])); ?>&text=<?php echo urlencode($project['title']); ?>" target="_blank" class="share-btn twitter" title="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="mailto:?subject=<?php echo urlencode($project['title']); ?>&body=<?php echo urlencode($systemUrl . 'project.php?url=' . urlencode($project['project_url'])); ?>" class="share-btn email" title="Email"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ******FOOTER****** --> 
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">DK<span>.</span></div>
                <p class="footer-copy">&copy; <?php echo date('Y'); ?> Donatien KANANE. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
 
    <!-- Javascript -->          
    <script type="text/javascript" src="<?php echo $systemUrl; ?>assets/plugins/popper.min.js"></script> 
    <script type="text/javascript" src="<?php echo $systemUrl; ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>    
    <script type="text/javascript" src="<?php echo $systemUrl; ?>assets/plugins/dark-mode-switch/dark-mode-switch.min.js"></script> 
    <!-- custom js -->
    <script type="text/javascript" src="<?php echo $systemUrl; ?>assets/js/main.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    $(document).ready(function() {
        // Mise à jour du compteur de caractères
        $('#commentContent').on('input', function() {
            const maxLength = 500;
            const currentLength = $(this).val().length;
            $('#charCount').text(currentLength);
            
            if (currentLength > maxLength) {
                $(this).val($(this).val().substring(0, maxLength));
                $('#charCount').text(maxLength);
            }
        });
        
        // Mise à jour du compteur de commentaires
        function updateCommentCount() {
            const count = $('.comment-item-card').length;
            $('#commentCount').text(count);
        }
        updateCommentCount();
        
        // Gestion du formulaire de commentaire
        $('#commentForm').on('submit', function(e) {
            e.preventDefault();
            
            const content = $('#commentContent').val().trim();
            const projectUrl = <?php echo json_encode($project['project_url']); ?>;
            
            if (!content) {
                showAlert('warning', 'Veuillez entrer un commentaire');
                return;
            }
            
            // Désactiver le bouton pendant l'envoi
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Envoi...');
            
            $.ajax({
                url: 'includes/handle_comment.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    project_url: projectUrl,
                    content: content
                }),
                success: function(response) {
                    if (response.success) {
                        // Ajouter le nouveau commentaire en haut de la liste
                        const newComment = `
                            <div class="comment-item-card" data-comment-id="${response.comment.id}">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="comment-avatar me-2">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">${response.comment.username}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> ${response.comment.created_at}
                                            </small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <button class="dropdown-item text-danger delete-comment" 
                                                            data-comment-id="${response.comment.id}">
                                                        <i class="fas fa-trash"></i> Supprimer
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="comment-content">
                                        ${response.comment.content}
                                    </div>
                            </div>
                        `;
                        
                        // Supprimer le message "Aucun commentaire" s'il existe
                        $('#commentsList .comment-empty-state').remove();
                        
                        // Ajouter le nouveau commentaire
                        $('#commentsList').prepend(newComment);
                        
                        // Vider le formulaire et réinitialiser le compteur
                        $('#commentContent').val('');
                        $('#charCount').text('0');
                        
                        // Mettre à jour le compteur de commentaires
                        updateCommentCount();
                        
                        // Afficher une notification de succès
                        showAlert('success', 'Commentaire publié avec succès !');
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    showAlert('danger', response.error || 'Une erreur est survenue');
                },
                complete: function() {
                    // Réactiver le bouton
                    submitBtn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Publier');
                }
            });
        });
        
        // Gestion de la suppression des commentaires
        $(document).on('click', '.delete-comment', function() {
            const commentId = $(this).data('comment-id');
            const commentElement = $(this).closest('.comment-item-card');
            
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
                return;
            }
            
            $.ajax({
                url: 'includes/delete_comment.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    comment_id: commentId
                }),
                success: function(response) {
                    if (response.success) {
                        commentElement.fadeOut(300, function() {
                            $(this).remove();
                            
                            // Mettre à jour le compteur de commentaires
                            updateCommentCount();
                            
                            // Si plus de commentaires, afficher le message
                            if ($('.comment-item-card').length === 0) {
                                $('#commentsList').html(`
                                    <div class="comment-empty-state">
                                        <i class="fas fa-comments"></i>
                                        <h5>Aucun commentaire pour le moment</h5>
                                        <p>Soyez le premier à partager votre avis sur ce projet !</p>
                                    </div>
                                `);
                            }
                        });
                        
                        showAlert('success', 'Commentaire supprimé avec succès !');
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    showAlert('danger', response.error || 'Une erreur est survenue');
                }
            });
        });
        
        // Fonction pour afficher les alertes
        function showAlert(type, message) {
            const alert = $(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-circle' : 'times-circle'}"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
            
            $('#commentForm').before(alert);
            setTimeout(() => alert.alert('close'), 3000);
        }
    });
    </script>
</body>
</html> 