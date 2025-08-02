<?php
$page_title = 'Composants - Askiaverse';
?>

<!-- Components Showcase -->
<div class="space-y-12">
    <!-- Buttons Section -->
    <section>
        <h2 class="text-3xl font-headings font-bold mb-8 text-gradient">Boutons</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="space-y-4">
                <h3 class="text-lg font-headings font-semibold">Primaire</h3>
                <button class="btn btn-primary">Bouton Primaire</button>
                <button class="btn btn-primary btn-large">Bouton Large</button>
                <button class="btn btn-primary" disabled>Désactivé</button>
            </div>
            <div class="space-y-4">
                <h3 class="text-lg font-headings font-semibold">Secondaire</h3>
                <button class="btn btn-secondary">Bouton Secondaire</button>
                <button class="btn btn-secondary btn-large">Bouton Large</button>
                <button class="btn btn-secondary" disabled>Désactivé</button>
            </div>
            <div class="space-y-4">
                <h3 class="text-lg font-headings font-semibold">Succès</h3>
                <button class="btn btn-success">Bouton Succès</button>
                <button class="btn btn-success btn-large">Bouton Large</button>
                <button class="btn btn-success" disabled>Désactivé</button>
            </div>
            <div class="space-y-4">
                <h3 class="text-lg font-headings font-semibold">Danger</h3>
                <button class="btn btn-danger">Bouton Danger</button>
                <button class="btn btn-danger btn-large">Bouton Large</button>
                <button class="btn btn-danger" disabled>Désactivé</button>
            </div>
        </div>
    </section>

    <!-- Cards Section -->
    <section>
        <h2 class="text-3xl font-headings font-bold mb-8 text-gradient">Cartes</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Carte Simple</h3>
                </div>
                <div class="card-body">
                    <p>Contenu de la carte avec du texte et des éléments.</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Carte avec Actions</h3>
                </div>
                <div class="card-body">
                    <p>Une carte avec des boutons d'action.</p>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary">Action</button>
                    <button class="btn btn-secondary">Annuler</button>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Carte Interactive</h3>
                </div>
                <div class="card-body">
                    <p>Une carte cliquable avec des effets de survol.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Forms Section -->
    <section>
        <h2 class="text-3xl font-headings font-bold mb-8 text-gradient">Formulaires</h2>
        <div class="max-w-2xl">
            <form class="space-y-6">
                <div>
                    <label for="name" class="form-label">Nom</label>
                    <input type="text" id="name" class="form-input" placeholder="Votre nom">
                </div>
                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" class="form-input" placeholder="votre@email.com">
                </div>
                <div>
                    <label for="message" class="form-label">Message</label>
                    <textarea id="message" class="form-textarea" rows="4" placeholder="Votre message"></textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="agree" class="form-checkbox">
                    <label for="agree" class="form-checkbox-label">J'accepte les conditions</label>
                </div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>
    </section>

    <!-- Navigation Section -->
    <section>
        <h2 class="text-3xl font-headings font-bold mb-8 text-gradient">Navigation</h2>
        <div class="space-y-6">
            <nav class="nav-demo">
                <a href="#" class="nav-link active">Accueil</a>
                <a href="#" class="nav-link">À propos</a>
                <a href="#" class="nav-link">Services</a>
                <a href="#" class="nav-link">Contact</a>
            </nav>
        </div>
    </section>

    <!-- Modals Section -->
    <section>
        <h2 class="text-3xl font-headings font-bold mb-8 text-gradient">Modales</h2>
        <div class="space-y-4">
            <button class="btn btn-primary" onclick="openDemoModal()">Ouvrir Modale</button>
            <button class="btn btn-secondary" onclick="openConfirmModal()">Modale de Confirmation</button>
        </div>
    </section>

    <!-- Alerts Section -->
    <section>
        <h2 class="text-3xl font-headings font-bold mb-8 text-gradient">Alertes</h2>
        <div class="space-y-4">
            <div class="alert alert-success">
                <div class="alert-icon">✓</div>
                <div class="alert-content">
                    <div class="alert-title">Succès</div>
                    <div class="alert-message">Opération réussie !</div>
                </div>
            </div>
            <div class="alert alert-warning">
                <div class="alert-icon">⚠</div>
                <div class="alert-content">
                    <div class="alert-title">Attention</div>
                    <div class="alert-message">Veuillez vérifier vos informations.</div>
                </div>
            </div>
            <div class="alert alert-error">
                <div class="alert-icon">✗</div>
                <div class="alert-content">
                    <div class="alert-title">Erreur</div>
                    <div class="alert-message">Une erreur s'est produite.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Progress Section -->
    <section>
        <h2 class="text-3xl font-headings font-bold mb-8 text-gradient">Barres de Progression</h2>
        <div class="space-y-4">
            <div>
                <div class="progress-label">Progression 25%</div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 25%"></div>
                </div>
            </div>
            <div>
                <div class="progress-label">Progression 50%</div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 50%"></div>
                </div>
            </div>
            <div>
                <div class="progress-label">Progression 75%</div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 75%"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Demo Modal -->
<div id="demo-modal" class="modal hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Exemple de Modale</h3>
            <button class="modal-close" onclick="closeDemoModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Ceci est un exemple de modale avec du contenu.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeDemoModal()">Fermer</button>
            <button class="btn btn-primary">Confirmer</button>
        </div>
    </div>
</div>

<!-- Confirm Modal -->
<div id="confirm-modal" class="modal hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Confirmation</h3>
            <button class="modal-close" onclick="closeConfirmModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir effectuer cette action ?</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeConfirmModal()">Annuler</button>
            <button class="btn btn-danger">Confirmer</button>
        </div>
    </div>
</div>

<script>
// Modal functions
function openDemoModal() {
    document.getElementById('demo-modal').classList.remove('hidden');
}

function closeDemoModal() {
    document.getElementById('demo-modal').classList.add('hidden');
}

function openConfirmModal() {
    document.getElementById('confirm-modal').classList.remove('hidden');
}

function closeConfirmModal() {
    document.getElementById('confirm-modal').classList.add('hidden');
}

// Close modals when clicking outside
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
});
</script> 