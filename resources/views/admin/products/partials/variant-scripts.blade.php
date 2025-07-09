// Variant Management JavaScript

// Modal Functions
function openAddVariantModal() {
    document.getElementById('addVariantModal').classList.remove('hidden');
}

function closeAddVariantModal() {
    document.getElementById('addVariantModal').classList.add('hidden');
    document.getElementById('addVariantForm').reset();
}

function openEditVariantModal() {
    document.getElementById('editVariantModal').classList.remove('hidden');
}

function closeEditVariantModal() {
    document.getElementById('editVariantModal').classList.add('hidden');
    document.getElementById('editVariantForm').reset();
}

function openUpdateStockModal() {
    document.getElementById('updateStockModal').classList.remove('hidden');
}

function closeUpdateStockModal() {
    document.getElementById('updateStockModal').classList.add('hidden');
    document.getElementById('updateStockForm').reset();
}

// Add Variant Form Handler
document.getElementById('addVariantForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    
    // Disable submit button
    submitBtn.disabled = true;
    submitBtn.textContent = 'Adding...';
    
    fetch('{{ route("admin.products.variants.store", $product) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification('Variant added successfully!', 'success');
            // Reload page to show new variant
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification(data.message || 'Error adding variant', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding variant', 'error');
    })
    .finally(() => {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.textContent = 'Add Variant';
    });
});

// Edit Variant
function editVariant(variantId) {
    // Fetch variant data
    fetch(`/admin/products/variants/${variantId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const variant = data.variant;
            
            // Populate edit form
            document.getElementById('edit_variant_id').value = variant.id;
            document.getElementById('edit_variant_type').value = variant.variant_type;
            document.getElementById('edit_variant_value').value = variant.variant_value;
            document.getElementById('edit_sku_suffix').value = variant.sku_suffix;
            document.getElementById('edit_price').value = variant.price;
            document.getElementById('edit_cost_price').value = variant.cost_price;
            document.getElementById('edit_is_active').checked = variant.is_active;
            
            openEditVariantModal();
        } else {
            showNotification('Error loading variant data', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error loading variant data', 'error');
    });
}

// Edit Variant Form Handler
document.getElementById('editVariantForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const variantId = document.getElementById('edit_variant_id').value;
    const submitBtn = this.querySelector('button[type="submit"]');
    
    // Disable submit button
    submitBtn.disabled = true;
    submitBtn.textContent = 'Updating...';
    
    fetch(`/admin/products/variants/${variantId}`, {
        method: 'PUT',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Variant updated successfully!', 'success');
            closeEditVariantModal();
            // Reload page to show updates
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification(data.message || 'Error updating variant', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating variant', 'error');
    })
    .finally(() => {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.textContent = 'Update Variant';
    });
});

// Delete Variant
function deleteVariant(variantId) {
    if (confirm('Are you sure you want to delete this variant? This action cannot be undone.')) {
        fetch(`/admin/products/variants/${variantId}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Variant deleted successfully!', 'success');
                // Remove row from table
                const row = document.getElementById(`variant-${variantId}`);
                if (row) {
                    row.remove();
                }
                // Reload page to update combinations
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showNotification(data.message || 'Error deleting variant', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error deleting variant', 'error');
        });
    }
}

// Update Stock
function updateStock(combinationId) {
    // Fetch current stock data
    fetch(`/admin/inventory/combinations/${combinationId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const inventory = data.inventory;
            
            // Populate stock form
            document.getElementById('combination_id').value = combinationId;
            document.getElementById('current_stock').value = inventory.current_stock || 0;
            document.getElementById('low_stock_threshold').value = inventory.low_stock_threshold || 10;
            
            openUpdateStockModal();
        } else {
            showNotification('Error loading stock data', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error loading stock data', 'error');
    });
}

// Update Stock Form Handler
document.getElementById('updateStockForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const combinationId = document.getElementById('combination_id').value;
    const submitBtn = this.querySelector('button[type="submit"]');
    
    // Disable submit button
    submitBtn.disabled = true;
    submitBtn.textContent = 'Updating...';
    
    fetch(`/admin/inventory/combinations/${combinationId}`, {
        method: 'PUT',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Stock updated successfully!', 'success');
            closeUpdateStockModal();
            // Reload page to show updates
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification(data.message || 'Error updating stock', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating stock', 'error');
    })
    .finally(() => {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.textContent = 'Update Stock';
    });
});

// Notification System
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 ${getNotificationClasses(type)}`;
    notification.textContent = message;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

function getNotificationClasses(type) {
    switch (type) {
        case 'success':
            return 'bg-green-500 text-white';
        case 'error':
            return 'bg-red-500 text-white';
        case 'warning':
            return 'bg-yellow-500 text-white';
        default:
            return 'bg-blue-500 text-white';
    }
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
        // Check which modal is open and close it
        if (!document.getElementById('addVariantModal').classList.contains('hidden')) {
            closeAddVariantModal();
        }
        if (!document.getElementById('editVariantModal').classList.contains('hidden')) {
            closeEditVariantModal();
        }
        if (!document.getElementById('updateStockModal').classList.contains('hidden')) {
            closeUpdateStockModal();
        }
    }
});

// Escape key to close modals
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddVariantModal();
        closeEditVariantModal();
        closeUpdateStockModal();
    }
});