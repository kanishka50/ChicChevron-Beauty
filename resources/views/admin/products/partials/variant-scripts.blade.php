<script>
// Variant Management JavaScript

// Modal Functions
function openAddVariantModal() {
    document.getElementById('addVariantModal').classList.remove('hidden');
}

function closeAddVariantModal() {
    document.getElementById('addVariantModal').classList.add('hidden');
    document.getElementById('addVariantForm').reset();
}

function openAddCombinationModal() {
    document.getElementById('addCombinationModal').classList.remove('hidden');
}
function closeAddCombinationModal() {
    document.getElementById('addCombinationModal').classList.add('hidden');
    document.getElementById('addCombinationForm').reset();
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
document.addEventListener('DOMContentLoaded', function() {
    const addVariantForm = document.getElementById('addVariantForm');
    if (addVariantForm) {
        addVariantForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const productId = '{{ $product->id }}';
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.textContent = 'Adding...';
            
            fetch(`/admin/products/${productId}/variants`, {
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
                    showNotification('Variant added successfully!', 'success');
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
                submitBtn.disabled = false;
                submitBtn.textContent = 'Add Variant';
            });
        });
    }

    // Edit Variant Form Handler
    const editVariantForm = document.getElementById('editVariantForm');
    if (editVariantForm) {
        editVariantForm.addEventListener('submit', function(e) {
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
                submitBtn.disabled = false;
                submitBtn.textContent = 'Update Variant';
            });
        });
    }

    // Update Stock Form Handler
    const updateStockForm = document.getElementById('updateStockForm');
    if (updateStockForm) {
        updateStockForm.addEventListener('submit', function(e) {
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
                submitBtn.disabled = false;
                submitBtn.textContent = 'Update Stock';
            });
        });
    }




     const editCombinationForm = document.getElementById('editCombinationForm');
if (editCombinationForm) {
    editCombinationForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const combinationId = document.getElementById('edit_combination_id').value;
        const submitBtn = this.querySelector('button[type="submit"]');
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.textContent = 'Updating...';
        
        fetch(`/admin/products/combinations/${combinationId}`, {
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
                showNotification('Combination price updated successfully!', 'success');
                closeEditCombinationModal();
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showNotification(data.message || 'Error updating combination', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error updating combination', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Update Price';
        });
    });
}




// Add Combination Form Handler
const addCombinationForm = document.getElementById('addCombinationForm');
if (addCombinationForm) {
    addCombinationForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const productId = '{{ $product->id }}';
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.textContent = 'Adding...';
        
        fetch(`/admin/products/${productId}/combinations`, {
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
                showNotification('Combination added successfully!', 'success');
                closeAddCombinationModal();
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showNotification(data.message || 'Error adding combination', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error adding combination', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Add Combination';
        });
    });
}
});

// Edit Variant
function editVariant(variantId) {
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
            
            // Populate edit form (REMOVE PRICE FIELDS)
            document.getElementById('edit_variant_id').value = variant.id;
            document.getElementById('edit_variant_type').value = variant.variant_type;
            document.getElementById('edit_variant_value').value = variant.variant_value;
            document.getElementById('edit_sku_suffix').value = variant.sku_suffix;
            document.getElementById('edit_is_active').checked = variant.is_active;
            // REMOVED: price, cost_price, discount_price
            
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
                const row = document.getElementById(`variant-${variantId}`);
                if (row) {
                    row.remove();
                }
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

// Notification System
function showNotification(message, type = 'info') {
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 ${getNotificationClasses(type)}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
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




function openEditCombinationModal() {
    document.getElementById('editCombinationModal').classList.remove('hidden');
}

function closeEditCombinationModal() {
    document.getElementById('editCombinationModal').classList.add('hidden');
    document.getElementById('editCombinationForm').reset();
}

function editCombination(combinationId) {
    fetch(`/admin/products/combinations/${combinationId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const combination = data.combination;
            
            // Populate form
            document.getElementById('edit_combination_id').value = combination.id;
            document.getElementById('combination_details').innerHTML = combination.variant_details;
            document.getElementById('edit_combination_price').value = combination.combination_price;
            document.getElementById('edit_combination_discount_price').value = combination.discount_price || '';
            document.getElementById('edit_combination_cost_price').value = combination.combination_cost_price;
            document.getElementById('edit_combination_is_active').checked = combination.is_active;
            
            openEditCombinationModal();
        } else {
            showNotification('Error loading combination data', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error loading combination data', 'error');
    });
}



// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
        if (!document.getElementById('addVariantModal').classList.contains('hidden')) {
            closeAddVariantModal();
        }
        if (!document.getElementById('editVariantModal').classList.contains('hidden')) {
            closeEditVariantModal();
        }
        if (!document.getElementById('updateStockModal').classList.contains('hidden')) {
            closeUpdateStockModal();
        }
        if (!document.getElementById('addCombinationModal').classList.contains('hidden')) {
            closeAddCombinationModal();
        }
        if (!document.getElementById('editCombinationModal').classList.contains('hidden')) {
            closeEditCombinationModal();
        }
    }
});

// Escape key to close modals
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddVariantModal();
        closeEditVariantModal();
        closeUpdateStockModal();
        closeAddCombinationModal();        
        closeEditCombinationModal(); 
    }
});
</script>