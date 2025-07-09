<!-- Add Stock Modal -->
<div id="addStockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add Stock</h3>
            <form id="addStockForm">
                @csrf
                <input type="hidden" id="add_product_id" name="product_id">
                <input type="hidden" id="add_variant_combination_id" name="variant_combination_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Product</label>
                    <div id="add_product_info" class="mt-1 p-2 bg-gray-50 rounded text-sm">
                        <!-- Product info will be populated here -->
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" 
                           name="quantity" 
                           id="add_quantity"
                           min="1"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                           required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Cost per Unit (LKR)</label>
                    <input type="number" 
                           name="cost_per_unit" 
                           id="add_cost_per_unit"
                           step="0.01" 
                           min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                           required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Reason</label>
                    <select name="reason" 
                            id="add_reason"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="Stock received">Stock received</option>
                        <option value="Purchase order">Purchase order</option>
                        <option value="Return from customer">Return from customer</option>
                        <option value="Production">Production</option>
                        <option value="Transfer in">Transfer in</option>
                        <option value="Manual addition">Manual addition</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Custom Reason (Optional)</label>
                    <input type="text" 
                           id="add_custom_reason"
                           placeholder="Enter custom reason if needed"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                
                <!-- Total Cost Display -->
                <div class="mb-4 p-3 bg-blue-50 rounded">
                    <div class="flex justify-between text-sm">
                        <span>Total Cost:</span>
                        <span id="add_total_cost" class="font-bold">LKR 0.00</span>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeAddStockModal()" 
                            class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                        Add Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>