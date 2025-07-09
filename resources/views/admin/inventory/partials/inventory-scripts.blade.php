<script>
// Global variables for current modals
let currentProductId = null;
let currentVariantCombinationId = null;
let currentStockLevel = 0;

// Modal Functions
function openAddStockModal() {
    document.getElementById('addStockModal').classList.remove('hidden');
}

function closeAddStockModal() {
    document.getElementById('addStockModal').classList.add('hidden');
    document.getElementById('addStockForm').reset();
    document.getElementById('add_total_cost').textContent = 'LKR 0.00';
}

function openAdjustStockModal() {
    document.getElementById('adjustStockModal').classList.remove('hidden');
}

function closeAdjustStockModal() {
    document.getElementById('adjustStockModal').classList.add('hidden');
    document.getElementById('adjustStockForm').reset();
}

function openStockDetailsModal() {
    document.getElementById('stockDetailsModal').classList.remove('hidden');
}

function closeStockDetailsModal() {
    document.getElementById('stockDetailsModal').classList.add('hidden');
}

// Add stock to specific item
function addStockToItem(productId, variantCombinationId) {
    currentProductId = productId;
    currentVariantCombinationId = variantCombinationId;
    
    // Set hidden fields
    document.getElementById('add_product_id').value = productId;
    document.getElementById('add_variant_combination_id').value = variantCombinationId || '';
    
    // Load product info
    loadProductInfo(productId, variantCombinationId, 'add_product_info');
    
    openAddStockModal();
}

// Adjust stock for specific item
function adjustStockItem(productId, variantCombinationId, currentStock) {
    currentProductId = productId;
    currentVariantCombinationId = variantCombinationId;
    currentStockLevel = currentStock;
    
    // Set hidden fields
    document.getElementById('adjust_product_id').value = productId;
    document.getElementById('adjust_variant_combination_id').value = variantCombinationId || '';
    document.getElementById('adjust_new_quantity').value = currentStock;
    
    // Load product info
    loadProductInfo(productId, variantCombinationId, 'adjust_product_info');
    document.getElementById('adjust_current_stock').textContent = currentStock + ' units';
    
    // Update summary
    updateAdjustmentSummary();
    
    openAdjustStockModal();
}

// View stock details
function viewStockDetails(productId, variantCombinationId) {
    currentProductId = productId;
    currentVariantCombinationId = variantCombinationId;
    
    openStockDetailsModal();
    loadStockDetails(productId, variantCombinationId);
}

// Load product information
function loadProductInfo(productId, variantCombinationId, targetElementId) {
    // This would typically make an AJAX call to get product info
    // For now, we'll use a placeholder
    const element = document.getElementById(targetElementId);
    element.innerHTML = `
        <div class="flex items-center">
            <div>
                <div class="font-medium">Loading product information...</div>
                <div class="text-gray-500">Product ID: ${productId}</div>
                ${variantCombinationId ? `<div class="text-gray-500">Variant ID: ${variantCombinationId}</div>` : ''}
            </div>
        </div>
    `;
}

// Load stock details with FIFO information
function loadStockDetails(productId, variantCombinationId) {
    const loadingHtml = '<div class="text-center py-4">Loading stock details...</div>';
    document.getElementById('details_batches_table').innerHTML = loadingHtml;
    document.getElementById('details_movements_table').innerHTML = loadingHtml;
    
    fetch('/admin/inventory/stock-details', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            variant_combination_id: variantCombinationId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            populateStockDetails(data.stock_details);
        } else {
            showNotification('Error loading stock details: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error loading stock details', 'error');
    });
}

// Populate stock details modal
function populateStockDetails(stockDetails) {
    const inventory = stockDetails.inventory;
    const batches = stockDetails.batches || [];
    
    // Update summary cards
    document.getElementById('details_current_stock').textContent = inventory.current_stock || 0;
    document.getElementById('details_reserved_stock').textContent = inventory.reserved_stock || 0;
    document.getElementById('details_available_stock').textContent = stockDetails.available_stock || 0;
    document.getElementById('details_average_cost').textContent = 'LKR ' + (stockDetails.average_cost || 0).toFixed(2);
    
    // Populate batches table
    const batchesTable = document.getElementById('details_batches_table');
    if (batches.length > 0) {
        batchesTable.innerHTML = batches.map(batch => {
            const receivedDate = new Date(batch.oldest_date);
            const ageInDays = Math.floor((new Date() - receivedDate) / (1000 * 60 * 60 * 24));
            const totalValue = batch.available_quantity * batch.cost_per_unit;
            
            return `
                <tr>
                    <td class="px-4 py-3 text-sm font-mono text-gray-900">${batch.batch_number}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">${batch.available_quantity}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">LKR ${parseFloat(batch.cost_per_unit).toFixed(2)}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">LKR ${totalValue.toFixed(2)}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">${receivedDate.toLocaleDateString()}</td>
                    <td class="px-4 py-3 text-sm ${ageInDays > 30 ? 'text-red-600' : ageInDays > 14 ? 'text-yellow-600' : 'text-green-600'}">${ageInDays} days</td>
                </tr>
            `;
        }).join('');
    } else {
        batchesTable.innerHTML = '<tr><td colspan="6" class="px-4 py-3 text-center text-gray-500">No batches found</td></tr>';
    }
    
    // Load recent movements
    loadRecentMovements(inventory.id);
}

// Load recent movements
function loadRecentMovements(inventoryId) {
    // This would make an AJAX call to get recent movements
    // For now, we'll show a placeholder
    document.getElementById('details_movements_table').innerHTML = 
        '<tr><td colspan="5" class="px-4 py-3 text-center text-gray-500">Loading recent movements...</td></tr>';
}

// Calculate total cost for add stock
function calculateTotalCost() {
    const quantity = parseFloat(document.getElementById('add_quantity').value) || 0;
    const costPerUnit = parseFloat(document.getElementById('add_cost_per_unit').value) || 0;
    const totalCost = quantity * costPerUnit;
    
    document.getElementById('add_total_cost').textContent = 'LKR ' + totalCost.toFixed(2);
}

// Update adjustment summary
function updateAdjustmentSummary() {
    const currentStock = currentStockLevel;
    const newStock = parseFloat(document.getElementById('adjust_new_quantity').value) || 0;
    const difference = newStock - currentStock;
    
    document.getElementById('adjust_summary_current').textContent = currentStock;
    document.getElementById('adjust_summary_new').textContent = newStock;
    
    const differenceElement = document.getElementById('adjust_summary_difference');
    differenceElement.textContent = (difference >= 0 ? '+' : '') + difference;
    differenceElement.className = 'font-bold ' + (difference >= 0 ? 'text-green-600' : 'text-red-600');
}

// Add stock from details modal
function addStockFromDetails() {
    closeStockDetailsModal();
    addStockToItem(currentProductId, currentVariantCombinationId);
}

// Adjust stock from details modal
function adjustStockFromDetails() {
    closeStockDetailsModal();
    adjustStockItem(currentProductId, currentVariantCombinationId, currentStockLevel);
}

// Form submission handlers
document.addEventListener('DOMContentLoaded', function() {
    // Add stock form
    const addStockForm = document.getElementById('addStockForm');
    if (addStockForm) {
        addStockForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            
            // Handle custom reason
            const customReason = document.getElementById('add_custom_reason').value;
            if (customReason) {
                formData.set('reason', customReason);
            }
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Adding...';
            
            fetch('/admin/inventory/add-stock', {
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
                    showNotification(`Stock added successfully! Batch: ${data.batch_number}`, 'success');
                    closeAddStockModal();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message || 'Error adding stock', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error adding stock', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Add Stock';
            });
        });
    }
    
    // Adjust stock form
    const adjustStockForm = document.getElementById('adjustStockForm');
    if (adjustStockForm) {
        adjustStockForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            
            // Handle custom reason
            const customReason = document.getElementById('adjust_custom_reason').value;
            if (customReason) {
                const selectedReason = formData.get('reason');
                formData.set('reason', selectedReason + ': ' + customReason);
            }
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Adjusting...';
            
            fetch('/admin/inventory/adjust-stock', {
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
                    showNotification(`Stock adjusted successfully! Change: ${data.adjustment}`, 'success');
                    closeAdjustStockModal();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message || 'Error adjusting stock', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error adjusting stock', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Adjust Stock';
            });
        });
    }
    
    // Add event listeners for calculations
    const quantityInput = document.getElementById('add_quantity');
    const costInput = document.getElementById('add_cost_per_unit');
    const newQuantityInput = document.getElementById('adjust_new_quantity');
    
    if (quantityInput) quantityInput.addEventListener('input', calculateTotalCost);
    if (costInput) costInput.addEventListener('input', calculateTotalCost);
    if (newQuantityInput) newQuantityInput.addEventListener('input', updateAdjustmentSummary);
});

// Notification function
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

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
        closeAddStockModal();
        closeAdjustStockModal();
        closeStockDetailsModal();
    }
});

// Escape key to close modals
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddStockModal();
        closeAdjustStockModal();
        closeStockDetailsModal();
    }
});
</script>