// Admin Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Theme Toggle
    const themeToggle = document.getElementById('themeToggle');
    const body = document.documentElement;
    
    function updateTheme(isDark) {
        body.setAttribute('data-theme', isDark ? 'dark' : 'light');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        themeToggle.innerHTML = `<i class="mdi mdi-weather-${isDark ? 'sunny' : 'night'}"></i>`;
    }

    // Initialize theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    updateTheme(savedTheme === 'dark');

    themeToggle.addEventListener('click', () => {
        const isDark = body.getAttribute('data-theme') === 'dark';
        updateTheme(!isDark);
    });

    // Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminLayout = document.querySelector('.admin-layout');
    
    sidebarToggle.addEventListener('click', () => {
        adminLayout.classList.toggle('collapsed');
        localStorage.setItem('sidebarCollapsed', adminLayout.classList.contains('collapsed'));
    });

    // Initialize sidebar state
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        adminLayout.classList.add('collapsed');
    }

    // Alert Close Buttons
    document.querySelectorAll('.alert-close').forEach(button => {
        button.addEventListener('click', () => {
            const alert = button.closest('.alert');
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    });

    // Search Box Functionality
    const searchBox = document.querySelector('.search-box input');
    if (searchBox) {
        searchBox.addEventListener('input', debounce(function(e) {
            const query = e.target.value.toLowerCase();
            // Implement search functionality here
        }, 300));
    }

    // User Menu Dropdown
    const userMenu = document.querySelector('.user-menu');
    if (userMenu) {
        userMenu.addEventListener('click', function(e) {
            this.classList.toggle('active');
            e.stopPropagation();
        });

        document.addEventListener('click', function() {
            userMenu.classList.remove('active');
        });
    }

    // Initialize Charts if they exist
    initializeCharts();
});

// Utility Functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function initializeCharts() {
    // Example chart initialization
    const statsChart = document.getElementById('statsChart');
    if (statsChart) {
        new Chart(statsChart, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Users',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: getComputedStyle(document.documentElement)
                        .getPropertyValue('--primary-color'),
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: getComputedStyle(document.documentElement)
                                .getPropertyValue('--border-color')
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
}

// Notifications
const notificationsToggle = document.getElementById('notificationsToggle');
if (notificationsToggle) {
    notificationsToggle.addEventListener('click', async () => {
        try {
            const response = await fetch('/admin/api/notifications');
            const notifications = await response.json();
            
            // Update notification panel
            updateNotificationPanel(notifications);
        } catch (error) {
            console.error('Failed to fetch notifications:', error);
        }
    });
}

function updateNotificationPanel(notifications) {
    // Implementation for updating the notifications panel
    console.log('Notifications updated:', notifications);
}
