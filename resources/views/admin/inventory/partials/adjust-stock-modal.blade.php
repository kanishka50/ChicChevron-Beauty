<!-- Adjust Stock Modal -->
<div id="adjustStockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Adjust Stock</h3>
            <form id="adjustStockForm" onsubmit="submitAdjustStock(event)">
                @csrf
                <input type="hidden" id="adjust_product_id" name="product_id">
                <input type="hidden" id="adjust_product_variant_id" name="product_variant_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Stock</label>
                    <input type="number" 
                           id="current_stock_display"
                           readonly
                           class="w-full px-3 py-2 border border-gray-200 rounded-md bg-gray-50 text-gray-700">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Stock Quantity</label>
                    <input type="number" 
                           name="new_quantity" 
                           id="new_quantity"
                           min="0"
                           required
                           onchange="updateAdjustmentSummary()"
                           oninput="updateAdjustmentSummary()"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Adjustment</label>
                    <select name="reason" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select reason</option>
                        <option value="Inventory Count">Physical Inventory Count</option>
                        <option value="Damaged">Damaged Goods</option>
                        <option value="Lost">Lost/Missing</option>
                        <option value="Error Correction">Error Correction</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <!-- Adjustment Summary -->
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="text-sm">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Current Stock:</span>
                            <span id="adjust_summary_current" class="font-medium text-gray-900">0</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">New Stock:</span>
                            <span id="adjust_summary_new" class="font-medium text-gray-900">0</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-yellow-200">
                            <span class="font-medium text-gray-700">Adjustment:</span>
                            <span id="adjust_summary_difference" class="font-semibold text-gray-900">0</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeAdjustStockModal()" 
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                        Adjust Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
