<!-- resources/views/admin/inventory/partials/inventory-scripts.blade.php -->
<script>
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
                alert(data.message);
                location.reload();
            } else {
                alert(data.message || 'Error adding stock');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding stock');
        });
    }
    
    // Adjust Stock Modal Functions
    function openAdjustStockModal(productId, productVariantId, currentStock) {
        document.getElementById('adjust_product_id').value = productId;
        document.getElementById('adjust_product_variant_id').value = productVariantId;
        document.getElementById('current_stock_display').value = currentStock;
        document.getElementById('adjustStockModal').classList.remove('hidden');
    }
    
    function closeAdjustStockModal() {
        document.getElementById('adjustStockModal').classList.add('hidden');
        document.getElementById('adjustStockForm').reset();
    }
    
    function submitAdjustStock(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        
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
                alert(data.message);
                location.reload();
            } else {
                alert(data.message || 'Error adjusting stock');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adjusting stock');
        });
    }
    
    // Stock Details Modal Functions
    function viewStockDetails(productId, productVariantId) {
        document.getElementById('stockDetailsModal').classList.remove('hidden');
        document.getElementById('stockDetailsContent').innerHTML = '<p class="text-center">Loading...</p>';
        
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
                document.getElementById('stockDetailsContent').innerHTML = 
                    '<p class="text-red-600">Error loading stock details</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('stockDetailsContent').innerHTML = 
                '<p class="text-red-600">An error occurred while loading stock details</p>';
        });
    }
    
    function closeStockDetailsModal() {
        document.getElementById('stockDetailsModal').classList.add('hidden');
    }
    
    function displayStockDetails(details) {
        let html = '<div class="space-y-4">';
        
        // Summary
        html += `
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-medium text-gray-900 mb-2">Stock Summary</h4>
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Total Stock:</span>
                        <span class="font-medium">${details.total_stock || 0}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Reserved:</span>
                        <span class="font-medium">${details.reserved_stock || 0}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Available:</span>
                        <span class="font-medium text-green-600">${details.available_stock || 0}</span>
                    </div>
                </div>
            </div>
        `;
        
        // FIFO Batches
        if (details.batches && details.batches.length > 0) {
            html += `
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Stock Batches (FIFO)</h4>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Batch #</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cost/Unit</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total Value</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
            `;
            
            details.batches.forEach(batch => {
                const totalValue = batch.quantity * batch.cost_per_unit;
                html += `
                    <tr>
                        <td class="px-4 py-2 text-sm">${batch.batch_number}</td>
                        <td class="px-4 py-2 text-sm">${new Date(batch.movement_date).toLocaleDateString()}</td>
                        <td class="px-4 py-2 text-sm">${batch.quantity}</td>
                        <td class="px-4 py-2 text-sm">LKR ${parseFloat(batch.cost_per_unit).toFixed(2)}</td>
                        <td class="px-4 py-2 text-sm font-medium">LKR ${totalValue.toFixed(2)}</td>
                    </tr>
                `;
            });
            
            html += `
                        </tbody>
                    </table>
                </div>
            `;
        } else {
            html += '<p class="text-gray-500 text-sm">No stock batches available</p>';
        }
        
        html += '</div>';
        document.getElementById('stockDetailsContent').innerHTML = html;
    }
    
    // Export function
    function exportMovements() {
        window.location.href = '{{ route("admin.inventory.export") }}';
    }
</script>