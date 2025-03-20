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
    <title><?php echo $debugMode ? '[DEBUG] ' : ''; ?><?php echo sanitizeOutput($project['title']); ?> - TutoLabPro</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo sanitizeOutput($project['description']); ?>">
    <meta name="author" content="<?php echo sanitizeOutput($profile['name'] ?? 'TutoLabPro'); ?>">    
    <link rel="shortcut icon" href="favicon.ico">  
    
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'> 
    
    <!-- FontAwesome JS -->
    <script defer src="<?php echo $systemUrl; ?>assets/fontawesome/js/all.js"></script>
    
    <!-- Global CSS -->
    <link rel="stylesheet" href="<?php echo $systemUrl; ?>assets/plugins/bootstrap/css/bootstrap.min.css">  
    
    <!-- Theme CSS -->  
    <link id="theme-style" rel="stylesheet" href="<?php echo $systemUrl; ?>assets/css/styles.css">
</head> 

<body>
    <!-- ******HEADER****** --> 
    <header class="header">
        <div class="container">     
            <div class="row align-items-center">
                <div class="col">         
                    <img class="profile-image img-fluid float-start rounded-circle" src="<?php echo $systemUrl; ?>assets/images/profile.png" alt="profile image" />
                    <div class="profile-content">
                        <h1 class="name"><?php echo sanitizeOutput($profile['name'] ?? 'James Lee'); ?></h1>
                        <h2 class="desc"><?php echo sanitizeOutput($profile['title'] ?? 'Web App Developer'); ?></h2>   
                        <ul class="social list-inline">
                            <?php if (!empty($profile['twitter_url'])): ?>
                            <li class="list-inline-item"><a href="<?php echo sanitizeOutput($profile['twitter_url']); ?>"><i class="fa-brands fa-x-twitter"></i></a></li>
                            <?php endif; ?>
                            <?php if (!empty($profile['linkedin_url'])): ?>
                            <li class="list-inline-item"><a href="<?php echo sanitizeOutput($profile['linkedin_url']); ?>"><i class="fa-brands fa-linkedin-in"></i></a></li>
                            <?php endif; ?>
                            <?php if (!empty($profile['github_username'])): ?>
                            <li class="list-inline-item"><a href="https://github.com/<?php echo sanitizeOutput($profile['github_username']); ?>"><i class="fa-brands fa-github-alt"></i></a></li>
                            <?php endif; ?>
                        </ul> 
                    </div><!--//profile-->
                </div><!--//col-->
                <div class="col-12 col-md-auto">
                    <div class="dark-mode-switch d-flex">
                        <div class="form-check form-switch mx-auto mx-md-0">
                            <input type="checkbox" class="form-check-input me-2" id="darkSwitch" />
                            <label class="custom-control-label" for="darkSwitch">Dark Mode</label>
                        </div>
                    </div><!--//dark-mode-switch-->
                    <a class="btn btn-cta-primary" href="index.php"><i class="fas fa-arrow-left"></i> Retour</a>        
                </div><!--//col-->
            </div><!--//row-->         
        </div><!--//container-->
    </header><!--//header-->
    
    <div class="container sections-wrapper py-5">
        <div class="row">
            <div class="primary col-lg-8 col-12">
                <section class="project section">
                    <div class="section-inner shadow-sm rounded">
                        <h2 class="heading"><?php echo sanitizeOutput($project['title']); ?></h2>
                        <?php if (!empty($project['image_url'])): ?>
                        <div class="project-image mb-4">
                            <img class="img-fluid rounded" src="<?php echo sanitizeOutput($project['image_url']); ?>" alt="<?php echo sanitizeOutput($project['title']); ?>" />
                        </div>
                        <?php endif; ?>
                        <div class="content">
                            <div class="project-description">
                                <?php echo nl2br(sanitizeOutput($project['description'])); ?>
                            </div>
                            <?php if (!empty($project['project_url'])): ?>
                            <div class="project-actions mt-4">
                                <a href="<?php echo sanitizeOutput($project['project_url']); ?>" class="btn btn-primary" target="_blank">
                                    <i class="fas fa-external-link-alt"></i> Voir le projet
                                </a>
                            </div>
                            <?php endif; ?>
                        </div><!--//content-->
                    </div><!--//section-inner-->                 
                </section><!--//section-->
            </div><!--//primary-->
            <div class="secondary col-lg-4 col-12">
                <aside class="info aside section">
                    <div class="section-inner shadow-sm rounded">
                        <h2 class="heading sr-only">Informations du projet</h2>
                        <div class="content">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-calendar"></i><span class="sr-only">Date de création:</span><?php echo formatDate($project['created_at']); ?></li>
                                <?php if ($project['is_featured']): ?>
                                <li><i class="fas fa-star"></i><span class="sr-only">Statut:</span>Projet mis en avant</li>
                                <?php endif; ?>
                            </ul>
                        </div><!--//content-->  
                    </div><!--//section-inner-->                 
                </aside><!--//aside-->
            </div><!--//secondary-->    
        </div><!--//row-->
    </div><!--//masonry-->

    <!-- Section Commentaires -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3><i class="fas fa-comments"></i> Commentaires</h3>
                    <span class="badge bg-primary" id="commentCount">0</span>
                </div>
                
                <?php if(!isUserLoggedIn()): ?>
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-user-circle fa-3x text-muted mb-3"></i>
                            <h5>Connectez-vous pour commenter</h5>
                            <p class="text-muted">Rejoignez la discussion et partagez votre avis sur ce projet</p>
                            <a href="login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" 
                               class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Se connecter
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Formulaire de commentaire -->
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="<?php echo $systemUrl; ?>assets/images/profile.png" class="rounded-circle me-2" width="40" height="40" alt="Avatar">
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
                                        <div class="form-text">
                                            <i class="fas fa-info-circle"></i> 
                                            Votre commentaire sera visible par tous les visiteurs
                                        </div>
                                        <div class="text-muted">
                                            <span id="charCount">0</span>/500 caractères
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Publier
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Liste des commentaires -->
                <div id="commentsList">
                    <?php
                    $comments = getProjectComments($db, $project['project_url']);
                    if (empty($comments)): ?>
                        <div class="card shadow-sm">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                <h5>Aucun commentaire pour le moment</h5>
                                <p class="text-muted">Soyez le premier à partager votre avis sur ce projet !</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="card mb-3 shadow-sm comment-item" data-comment-id="<?php echo $comment['id']; ?>">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="<?php echo $systemUrl; ?>assets/images/profile.png" 
                                             class="rounded-circle me-2" width="40" height="40" alt="Avatar">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0"><?php echo htmlspecialchars($comment['username']); ?></h6>
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> 
                                                <?php echo date('d/m/Y H:i', strtotime($comment['created_at'])); ?>
                                            </small>
                                        </div>
                                        <?php if (isUserLoggedIn() && $_SESSION['user_id'] == $comment['user_id']): ?>
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted" type="button" data-bs-toggle="dropdown">
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
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ******FOOTER****** --> 
    <footer class="footer">
        <div class="container text-center">
            <small class="copyright">Designed with <span class="sr-only">love</span><i class="fas fa-heart"></i> by <a href="https://themes.3rdwavemedia.com" target="_blank">Xiaoying Riley</a> for developers</small>
        </div><!--//container-->
    </footer><!--//footer-->
 
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
            const count = $('.comment-item').length;
            $('#commentCount').text(count);
        }
        updateCommentCount();
        
        // Gestion du formulaire de commentaire
        $('#commentForm').on('submit', function(e) {
            e.preventDefault();
            
            const content = $('#commentContent').val().trim();
            const projectUrl = '<?php echo $project['project_url']; ?>';
            
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
                            <div class="card mb-3 shadow-sm comment-item" data-comment-id="${response.comment.id}">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="<?php echo $systemUrl; ?>assets/images/profile.png" 
                                             class="rounded-circle me-2" width="40" height="40" alt="Avatar">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">${response.comment.username}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> ${response.comment.created_at}
                                            </small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted" type="button" data-bs-toggle="dropdown">
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
                            </div>
                        `;
                        
                        // Supprimer le message "Aucun commentaire" s'il existe
                        $('#commentsList .card:not(.comment-item)').remove();
                        
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
            const commentElement = $(this).closest('.comment-item');
            
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
                            if ($('.comment-item').length === 0) {
                                $('#commentsList').html(`
                                    <div class="card shadow-sm">
                                        <div class="card-body text-center py-5">
                                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                            <h5>Aucun commentaire pour le moment</h5>
                                            <p class="text-muted">Soyez le premier à partager votre avis sur ce projet !</p>
                                        </div>
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