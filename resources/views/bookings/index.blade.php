<x-admin-layout>
    <div class="min-h-screen bg-gray-50 py-4 md:py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            
            <!-- Search and Filters Component -->
            <x-booking.search-filters />
            
            <!-- Statistics Dashboard Component -->
            <x-booking.statistics-dashboard :stats="$stats" />
            
            <!-- Bookings Table Component -->
            <x-booking.table :bookings="$bookings" />
            
            <!-- Bulk Actions Modal Component -->
            <x-booking.bulk-actions-modal />
        </div>
    </div>

    <!-- Global JavaScript for Quick Actions -->
    <script>
        // CSRF Token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Quick Actions Functions
        function quickConfirm(bookingId) {
            if (confirm('Confirm this booking?')) {
                performQuickAction(bookingId, 'confirm', 'confirming');
            }
        }

        function quickActivate(bookingId) {
            if (confirm('Mark equipment as picked up and activate booking?')) {
                performQuickAction(bookingId, 'activate', 'activating');
            }
        }

        function quickCancel(bookingId) {
            if (confirm('Cancel this booking?')) {
                performQuickAction(bookingId, 'cancel', 'cancelling');
            }
        }

        function performQuickAction(bookingId, action, actionText) {
            // Show loading state on buttons
            const buttons = document.querySelectorAll(`[onclick*="${bookingId}"]`);
            buttons.forEach(btn => {
                btn.innerHTML = `<i class="fas fa-spinner fa-spin"></i>`;
                btn.disabled = true;
            });

            fetch(`/bookings/${bookingId}/quick-${action}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showNotification(`Booking successfully ${data.action}!`, 'success');
                    // Reload page after short delay
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification(`Error ${actionText} booking: ${data.message}`, 'error');
                    // Restore buttons
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification(`An error occurred while ${actionText} the booking.`, 'error');
                // Restore buttons
                setTimeout(() => {
                    location.reload();
                }, 2000);
            });
        }

        // Notification System
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notification => notification.remove());

            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg max-w-sm transition-all duration-300 transform translate-x-full`;
            
            // Set colors based on type
            const colors = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                info: 'bg-blue-500 text-white',
                warning: 'bg-yellow-500 text-white'
            };
            
            notification.className += ` ${colors[type]}`;
            
            // Set content
            notification.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0">
                        <button class="inline-flex text-white hover:opacity-75" onclick="this.parentElement.parentElement.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            
            // Add to DOM
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }

        // Auto-refresh functionality (optional)
        let autoRefreshInterval;
        function startAutoRefresh() {
            autoRefreshInterval = setInterval(() => {
                // Only refresh if no modals are open
                if (document.getElementById('bulk-actions-modal').classList.contains('hidden')) {
                    // Check for updates without full page reload
                    checkForUpdates();
                }
            }, 30000); // Check every 30 seconds
        }

        function stopAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }
        }

        function checkForUpdates() {
            // This could be implemented to check for booking updates via AJAX
            // For now, we'll do a simple page reload
            // location.reload();
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Uncomment to enable auto-refresh
            // startAutoRefresh();
            
            // Close modals when clicking outside
            document.addEventListener('click', function(event) {
                const modal = document.getElementById('bulk-actions-modal');
                if (event.target === modal) {
                    hideBulkActionsModal();
                }
            });
        });

        // Stop auto-refresh when page is hidden
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopAutoRefresh();
            } else {
                // startAutoRefresh();
            }
        });
    </script>

    <!-- Custom Styles for Mobile Responsiveness -->
    <style>
        @media (max-width: 640px) {
            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .mobile-card {
                margin-bottom: 1rem;
                border-radius: 0.5rem;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            }
            
            .mobile-action-button {
                padding: 0.5rem 1rem;
                font-size: 0.75rem;
                border-radius: 0.375rem;
            }
        }
        
        /* Loading Animation */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .fa-spin {
            animation: spin 1s linear infinite;
        }
        
        /* Notification Animation */
        .notification {
            transition: transform 0.3s ease-in-out;
        }
        
        /* Touch-friendly buttons */
        @media (hover: none) and (pointer: coarse) {
            button, a {
                min-height: 44px;
                min-width: 44px;
            }
        }
    </style>
</x-admin-layout>
