<!-- Adjust Stock Modal -->
<div id="adjustStockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Adjust Stock</h3>
            <form id="adjustStockForm">
                @csrf
                <input type="hidden" id="adjust_product_id" name="product_id">
                <input type="hidden" id="adjust_variant_combination_id" name="variant_combination_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Product</label>
                    <div id="adjust_product_info" class="mt-1 p-2 bg-gray-50 rounded text-sm">
                        <!-- Product info will be populated here -->
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Current Stock</label>
                    <div id="adjust_current_stock" class="mt-1 p-2 bg-gray-100 rounded text-sm font-medium">
                        <!-- Current stock will be displayed here -->
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">New Stock Quantity</label>
                    <input type="number" 
                           name="new_quantity" 
                           id="adjust_new_quantity"
                           min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                           required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Reason for Adjustment</label>
                    <select name="reason" 
                            id="adjust_reason"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="Manual adjustment">Manual adjustment</option>
                        <option value="Stock count correction">Stock count correction</option>
                        <option value="Damaged goods">Damaged goods</option>
                        <option value="Expired products">Expired products</option>
                        <option value="Lost/Stolen">Lost/Stolen</option>
                        <option value="Transfer correction">Transfer correction</option>
                        <option value="System error correction">System error correction</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Additional Notes (Optional)</label>
                    <textarea id="adjust_custom_reason"
                              rows="2"
                              placeholder="Enter additional details about this adjustment"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                </div>
                
                <!-- Adjustment Summary -->
                <div class="mb-4 p-3 bg-yellow-50 rounded">
                    <div class="text-sm">
                        <div class="flex justify-between mb-1">
                            <span>Current Stock:</span>
                            <span id="adjust_summary_current" class="font-medium">0</span>
                        </div>
                        <div class="flex justify-between mb-1">
                            <span>New Stock:</span>
                            <span id="adjust_summary_new" class="font-medium">0</span>
                        </div>
                        <div class="flex justify-between border-t pt-1">
                            <span>Adjustment:</span>
                            <span id="adjust_summary_difference" class="font-bold">0</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeAdjustStockModal()" 
                            class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Adjust Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>