/**
 * Admin Core JavaScript - Askiaverse
 * Common functionality for admin pages
 */

class AdminCore {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initializeComponents();
    }

    setupEventListeners() {
        // Global admin event listeners
        document.addEventListener('DOMContentLoaded', () => {
            this.setupNavigation();
            this.setupUserInfo();
        });
    }

    initializeComponents() {
        // Initialize common components
        this.setupTooltips();
        this.setupConfirmations();
    }

    setupNavigation() {
        // Handle navigation active states
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.admin-nav .nav-link');
        
        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    }

    setupUserInfo() {
        // Handle user info display
        const userInfo = document.querySelector('.user-info');
        if (userInfo) {
            // Add any user-specific functionality
        }
    }

    setupTooltips() {
        // Initialize tooltips for admin interface
        const tooltipElements = document.querySelectorAll('[title]');
        tooltipElements.forEach(element => {
            // Add tooltip functionality if needed
        });
    }

    setupConfirmations() {
        // Setup confirmation dialogs for destructive actions
        const confirmButtons = document.querySelectorAll('[data-confirm]');
        confirmButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const message = button.getAttribute('data-confirm');
                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        });
    }

    // Utility methods
    showNotification(message, type = 'info') {
        // Show admin notifications
        const notification = document.createElement('div');
        notification.className = `admin-notification admin-notification-${type}`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    showLoading() {
        // Show loading state
        const loader = document.createElement('div');
        loader.className = 'admin-loader';
        loader.innerHTML = '<div class="spinner"></div>';
        document.body.appendChild(loader);
    }

    hideLoading() {
        // Hide loading state
        const loader = document.querySelector('.admin-loader');
        if (loader) {
            loader.remove();
        }
    }

    // API helpers
    async apiRequest(url, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin'
        };

        const finalOptions = { ...defaultOptions, ...options };

        try {
            this.showLoading();
            const response = await fetch(url, finalOptions);
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.error || 'Une erreur est survenue');
            }
            
            return data;
        } catch (error) {
            this.showNotification(error.message, 'error');
            throw error;
        } finally {
            this.hideLoading();
        }
    }
}

// Initialize admin core
const adminCore = new AdminCore();

// Export for use in other modules
window.AdminCore = AdminCore; 