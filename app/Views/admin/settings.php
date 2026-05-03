<div class="admin-settings">
    <div class="form-header">
        <h1>Paramètres d'Administration</h1>
    </div>

    <div class="settings-container">
        <div class="settings-section">
            <h2>À propos du Site</h2>
            <div class="info-box">
                <p><strong>Version:</strong> 1.0.0</p>
                <p><strong>PHP:</strong> <?php echo phpversion(); ?></p>
                <p><strong>Base de données:</strong> MySQL</p>
            </div>
        </div>

        <div class="settings-section">
            <h2>Maintenance</h2>
            <p class="text-muted">Les fonctionnalités de maintenance seront disponibles bientôt.</p>
        </div>

        <div class="settings-section">
            <h2>Sécurité</h2>
            <div class="settings-group">
                <h3>Authentification Administrateur</h3>
                <p>La sécurité est assurée par:</p>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Hachage des mots de passe (bcrypt)</li>
                    <li><i class="fas fa-check"></i> Protection CSRF sur tous les formulaires</li>
                    <li><i class="fas fa-check"></i> Sessions sécurisées</li>
                    <li><i class="fas fa-check"></i> Requêtes préparées contre l'injection SQL</li>
                    <li><i class="fas fa-check"></i> Logs d'audit de toutes les actions</li>
                </ul>
            </div>
        </div>

        <div class="settings-section">
            <h2>Aide et Documentation</h2>
            <div class="help-links">
                <a href="#" class="help-link" title="Venant bientôt">
                    <i class="fas fa-book"></i>
                    Documentation Complète
                </a>
                <a href="#" class="help-link" title="Venant bientôt">
                    <i class="fas fa-headset"></i>
                    Support
                </a>
            </div>
        </div>
    </div>
</div>
