<!-- resources/views/admin/inventory/partials/add-stock-modal.blade.php -->
<div id="addStockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add Stock</h3>
            <form id="addStockForm" onsubmit="submitAddStock(event)">
                @csrf
                <input type="hidden" id="add_product_id" name="product_id">
                <input type="hidden" id="add_product_variant_id" name="product_variant_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <input type="number" 
                           name="quantity" 
                           min="1"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cost per Unit (LKR)</label>
                    <input type="number" 
                           name="cost_per_unit" 
                           step="0.01"
                           min="0"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                    <select name="reason" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select reason</option>
                        <option value="Purchase">Purchase</option>
                        <option value="Return">Customer Return</option>
                        <option value="Production">Production</option>
                        <option value="Transfer">Transfer</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeAddStockModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                        Add Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- resources/views/admin/inventory/partials/adjust-stock-modal.blade.php -->
<div id="adjustStockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Adjust Stock</h3>
            <form id="adjustStockForm" onsubmit="submitAdjustStock(event)">
                @csrf
                <input type="hidden" id="adjust_product_id" name="product_id">
                <input type="hidden" id="adjust_product_variant_id" name="product_variant_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Stock</label>
                    <input type="number" 
                           id="current_stock_display"
                           readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Stock Quantity</label>
                    <input type="number" 
                           name="new_quantity" 
                           min="0"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Adjustment</label>
                    <select name="reason" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select reason</option>
                        <option value="Inventory Count">Physical Inventory Count</option>
                        <option value="Damaged">Damaged Goods</option>
                        <option value="Lost">Lost/Missing</option>
                        <option value="Error Correction">Error Correction</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeAdjustStockModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Adjust Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- resources/views/admin/inventory/partials/stock-details-modal.blade.php -->
<div id="stockDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-2xl max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Stock Details</h3>
            <div id="stockDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="flex justify-end mt-4">
                <button type="button" onclick="closeStockDetailsModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>