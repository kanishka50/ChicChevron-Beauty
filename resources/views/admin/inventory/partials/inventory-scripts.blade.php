<script>
    // Current product/variant info for modals
    let currentProductId = null;
    let currentVariantId = null;
    let currentStock = 0;
    
    // Add Stock Modal Functions
    function openAddStockModal(productId, productVariantId) {
        document.getElementById('add_product_id').value = productId;
        document.getElementById('add_product_variant_id').value = productVariantId;
        document.getElementById('addStockModal').classList.remove('hidden');
    }
    
    function closeAddStockModal() {
        document.getElementById('addStockModal').classList.add('hidden');
        document.getElementById('addStockForm').reset();
    }
    
    function submitAddStock(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        
        // Show loading state
        submitButton.disabled = true;
        submitButton.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Adding...';
        
        fetch('{{ route("admin.inventory.add-stock") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
                showNotification(data.message || 'Error adding stock', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
            showNotification('An error occurred while adding stock', 'error');
        });
    }
    
    // Adjust Stock Modal Functions
    function openAdjustStockModal(productId, productVariantId, currentStockValue) {
        currentProductId = productId;
        currentVariantId = productVariantId;
        currentStock = currentStockValue;
        
        document.getElementById('adjust_product_id').value = productId;
        document.getElementById('adjust_product_variant_id').value = productVariantId;
        document.getElementById('current_stock_display').value = currentStockValue;
        document.getElementById('adjust_summary_current').textContent = currentStockValue;
        document.getElementById('adjustStockModal').classList.remove('hidden');
    }
    
    function closeAdjustStockModal() {
        document.getElementById('adjustStockModal').classList.add('hidden');
        document.getElementById('adjustStockForm').reset();
        document.getElementById('adjust_summary_new').textContent = '0';
        document.getElementById('adjust_summary_difference').textContent = '0';
    }
    
    function updateAdjustmentSummary() {
        const newQuantity = parseInt(document.getElementById('new_quantity').value) || 0;
        const difference = newQuantity - currentStock;
        
        document.getElementById('adjust_summary_new').textContent = newQuantity;
        
        const differenceElement = document.getElementById('adjust_summary_difference');
        differenceElement.textContent = (difference >= 0 ? '+' : '') + difference;
        differenceElement.className = difference >= 0 ? 'font-semibold text-green-600' : 'font-semibold text-red-600';
    }
    
    function submitAdjustStock(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        
        // Show loading state
        submitButton.disabled = true;
        submitButton.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Adjusting...';
        
        fetch('{{ route("admin.inventory.adjust-stock") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
                showNotification(data.message || 'Error adjusting stock', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
            showNotification('An error occurred while adjusting stock', 'error');
        });
    }
    
    // Stock Details Modal Functions
    function viewStockDetails(productId, productVariantId) {
        currentProductId = productId;
        currentVariantId = productVariantId;
        
        document.getElementById('stockDetailsModal').classList.remove('hidden');
        
        // Show loading state
        document.getElementById('details_product_info').innerHTML = '<p class="text-center text-gray-500">Loading...</p>';
        document.getElementById('details_current_stock').textContent = '-';
        document.getElementById('details_reserved_stock').textContent = '-';
        document.getElementById('details_available_stock').textContent = '-';
        document.getElementById('details_average_cost').textContent = '-';
        document.getElementById('details_batches_table').innerHTML = '<tr><td colspan="6" class="text-center py-4 text-gray-500">Loading...</td></tr>';
        document.getElementById('details_movements_table').innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-500">Loading...</td></tr>';
        
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('product_variant_id', productVariantId);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch('{{ route("admin.inventory.stock-details") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayStockDetails(data.stock_details);
            } else {
                document.getElementById('details_product_info').innerHTML = 
                    '<p class="text-red-600">Error loading stock details</p>';
                document.getElementById('details_batches_table').innerHTML = 
                    '<tr><td colspan="6" class="text-center py-4 text-red-600">Error loading data</td></tr>';
                document.getElementById('details_movements_table').innerHTML = 
                    '<tr><td colspan="5" class="text-center py-4 text-red-600">Error loading data</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('details_product_info').innerHTML = 
                '<p class="text-red-600">An error occurred while loading stock details</p>';
        });
    }
    
    function closeStockDetailsModal() {
        document.getElementById('stockDetailsModal').classList.add('hidden');
    }
    
    function displayStockDetails(details) {
        // Display product info
        let productInfo = '<div class="flex items-center space-x-4">';
        if (details.product) {
            productInfo += `
                <div>
                    <h4 class="font-semibold text-gray-900">${details.product.name}</h4>
                    <p class="text-sm text-gray-600">SKU: ${details.product.sku || 'N/A'}</p>
                    ${details.variant ? `<p class="text-sm text-gray-600">Variant: ${details.variant.name}</p>` : ''}
                </div>
            `;
        }
        productInfo += '</div>';
        document.getElementById('details_product_info').innerHTML = productInfo;
        
        // Display summary
        document.getElementById('details_current_stock').textContent = details.total_stock || 0;
        document.getElementById('details_reserved_stock').textContent = details.reserved_stock || 0;
        document.getElementById('details_available_stock').textContent = details.available_stock || 0;
        document.getElementById('details_average_cost').textContent = details.average_cost ? 
            `LKR ${parseFloat(details.average_cost).toFixed(2)}` : 'LKR 0.00';
        
        // Display batches
        let batchesHtml = '';
        if (details.batches && details.batches.length > 0) {
            details.batches.forEach(batch => {
                const totalValue = batch.quantity * batch.cost_per_unit;
                const receivedDate = new Date(batch.movement_date);
                const ageInDays = Math.floor((new Date() - receivedDate) / (1000 * 60 * 60 * 24));
                
                batchesHtml += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-mono text-gray-600">${batch.batch_number}</td>
                        <td class="px-4 py-3 text-sm text-center font-medium">${batch.quantity}</td>
                        <td class="px-4 py-3 text-sm">LKR ${parseFloat(batch.cost_per_unit).toFixed(2)}</td>
                        <td class="px-4 py-3 text-sm font-medium">LKR ${totalValue.toFixed(2)}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">${receivedDate.toLocaleDateString()}</td>
                        <td class="px-4 py-3 text-sm text-center ${ageInDays > 90 ? 'text-red-600 font-medium' : 'text-gray-600'}">${ageInDays}</td>
                    </tr>
                `;
            });
        } else {
            batchesHtml = '<tr><td colspan="6" class="px-4 py-4 text-center text-gray-500">No stock batches available</td></tr>';
        }
        document.getElementById('details_batches_table').innerHTML = batchesHtml;
        
        // Display recent movements
        let movementsHtml = '';
        if (details.movements && details.movements.length > 0) {
            details.movements.forEach(movement => {
                const moveDate = new Date(movement.movement_date);
                let typeClass = '';
                let typeIcon = '';
                
                if (movement.movement_type === 'in') {
                    typeClass = 'bg-green-100 text-green-700';
                    typeIcon = '<path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>';
                } else if (movement.movement_type === 'out') {
                    typeClass = 'bg-red-100 text-red-700';
                    typeIcon = '<path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>';
                } else {
                    typeClass = 'bg-yellow-100 text-yellow-700';
                    typeIcon = '<path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>';
                }
                
                movementsHtml += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-600">${moveDate.toLocaleDateString()}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${typeClass}">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">${typeIcon}</svg>
                                ${movement.movement_type.charAt(0).toUpperCase() + movement.movement_type.slice(1)}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-center font-semibold ${movement.quantity >= 0 ? 'text-green-600' : 'text-red-600'}">
                            ${movement.quantity >= 0 ? '+' : ''}${movement.quantity}
                        </td>
                        <td class="px-4 py-3 text-sm font-mono text-gray-600">${movement.batch_number}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">${movement.reason || '-'}</td>
                    </tr>
                `;
            });
        } else {
            movementsHtml = '<tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">No recent movements</td></tr>';
        }
        document.getElementById('details_movements_table').innerHTML = movementsHtml;
    }
    
    function addStockFromDetails() {
        closeStockDetailsModal();
        openAddStockModal(currentProductId, currentVariantId);
    }
    
    function adjustStockFromDetails() {
        closeStockDetailsModal();
        const currentStockElement = document.getElementById('details_current_stock');
        const currentStockValue = parseInt(currentStockElement.textContent) || 0;
        openAdjustStockModal(currentProductId, currentVariantId, currentStockValue);
    }
    
    // Export function
    function exportMovements() {
        window.location.href = '{{ route("admin.inventory.export") }}';
    }
    
    // Notification function
    function showNotification(message, type = 'info') {
        // Remove any existing notifications
        const existingNotifications = document.querySelectorAll('.notification-toast');
        existingNotifications.forEach(n => n.remove());
        
        // Create new notification
        const notification = document.createElement('div');
        notification.className = `notification-toast fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-[9999] transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
        
        notification.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${type === 'success' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>' :
                        type === 'error' ?
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                    }
                </svg>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 10);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
    
    // Close modals when clicking outside
    document.getElementById('addStockModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddStockModal();
        }
    });
    
    document.getElementById('adjustStockModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAdjustStockModal();
        }
    });
    
    document.getElementById('stockDetailsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeStockDetailsModal();
        }
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddStockModal();
            closeAdjustStockModal();
            closeStockDetailsModal();
        }
    });
</script>

<style>
/* Loading animation */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Notification animation */
.notification-toast {
    transform: translateX(100%);
}
</style>