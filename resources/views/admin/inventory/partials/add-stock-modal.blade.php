<!-- Add Stock Modal -->
<div id="addStockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Add Stock</h3>
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
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cost per Unit (LKR)</label>
                    <input type="number" 
                           name="cost_per_unit" 
                           step="0.01"
                           min="0"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                    <select name="reason" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select reason</option>
                        <option value="Purchase">Purchase</option>
                        <option value="Return">Customer Return</option>
                        <option value="Production">Production</option>
                        <option value="Transfer">Transfer</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeAddStockModal()" 
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                        Add Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

