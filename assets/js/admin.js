/* Admin JavaScript Utilities */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tab switching
    initializeTabs();
    
    // Initialize delete confirmations
    initializeDeleteConfirms();
});

function initializeTabs() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabName = this.dataset.tab;
            const container = this.closest('.tabs');
            
            if (!container) return;
            
            // Remove active class from all tabs in this container
            const siblings = container.parentElement.querySelectorAll('.tab-btn');
            siblings.forEach(sibling => sibling.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Hide all content
            const contents = container.parentElement.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.remove('active'));
            
            // Show selected content
            const selectedContent = document.getElementById(tabName);
            if (selectedContent) {
                selectedContent.classList.add('active');
            }
        });
    });
}

function initializeDeleteConfirms() {
    const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
    
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ? Cette action ne peut pas être annulée.')) {
                e.preventDefault();
            }
        });
    });
}

// Slug generation helper
function generateSlug(text) {
    return text
        .toLowerCase()
        .trim()
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
}

// Auto-generate slug from title
function initializeSlugGeneration() {
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            if (slugInput.value === '' || slugInput.value === generateSlug(titleInput.defaultValue || '')) {
                slugInput.value = generateSlug(this.value);
            }
        });
    }
}

// Show toast notifications
function showNotification(message, type = 'info', duration = 3000) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, duration);
}

// Confirm before navigation if form is dirty
let formDirty = false;

function initializeFormDirtyDetection() {
    const forms = document.querySelectorAll('.admin-form');
    
    forms.forEach(form => {
        form.addEventListener('change', function() {
            formDirty = true;
        });
        
        form.addEventListener('submit', function() {
            formDirty = false;
        });
    });
}

window.addEventListener('beforeunload', function(e) {
    if (formDirty) {
        e.preventDefault();
        e.returnValue = '';
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeFormDirtyDetection();
    initializeSlugGeneration();
});
