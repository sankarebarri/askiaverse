/**
 * Public Core JavaScript - Askiaverse
 * Common functionality for public pages
 */

class PublicCore {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initializeComponents();
    }

    setupEventListeners() {
        document.addEventListener('DOMContentLoaded', () => {
            this.setupUserMenu();
            this.setupNavigation();
        });
    }

    initializeComponents() {
        // Initialize common components
        this.setupTooltips();
    }

    setupUserMenu() {
        const userMenuBtn = document.getElementById('user-menu-btn');
        const userDropdown = document.getElementById('user-dropdown');
        
        if (userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener('click', function() {
                this.classList.toggle('open');
                userDropdown.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.user-menu')) {
                    userMenuBtn.classList.remove('open');
                    userDropdown.classList.add('hidden');
                }
            });
        }
    }

    setupNavigation() {
        // Handle navigation active states
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.main-nav .nav-link');
        
        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    }

    setupTooltips() {
        // Initialize tooltips for public interface
        const tooltipElements = document.querySelectorAll('[title]');
        tooltipElements.forEach(element => {
            // Add tooltip functionality if needed
        });
    }

    // Utility methods
    showNotification(message, type = 'info') {
        // Show public notifications
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    showLoading() {
        // Show loading state
        const loader = document.createElement('div');
        loader.className = 'loader';
        loader.innerHTML = '<div class="spinner"></div>';
        document.body.appendChild(loader);
    }

    hideLoading() {
        // Hide loading state
        const loader = document.querySelector('.loader');
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

// Initialize public core
const publicCore = new PublicCore();

// Export for use in other modules
window.PublicCore = PublicCore; 