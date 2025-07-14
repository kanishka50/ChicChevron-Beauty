<!-- Add Variant Modal -->
<div id="addVariantModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Variant</h3>
            <form id="addVariantForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Variant Type</label>
                    <select name="variant_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="size">Size</option>
                        <option value="color">Color</option>
                        <option value="scent">Scent</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Variant Value</label>
                    <input type="text" 
                           name="variant_value" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                           placeholder="e.g., Large, Red, Rose" 
                           required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">SKU Suffix</label>
                    <input type="text" 
                           name="sku_suffix" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                           placeholder="e.g., LG, RD, RSE" 
                           required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Price (LKR)</label>
                    <input type="number" 
                           name="price" 
                           step="0.01" 
                           min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                           required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Discount Price (LKR)</label>
                    <input type="number" 
                        name="discount_price" 
                        step="0.01" 
                        min="0"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                        placeholder="Leave empty if no discount">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Cost Price (LKR)</label>
                    <input type="number" 
                           name="cost_price" 
                           step="0.01" 
                           min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                           required>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeAddVariantModal()" 
                            class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Add Variant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Variant Modal -->
<div id="editVariantModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Variant</h3>
            <form id="editVariantForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_variant_id" name="variant_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Variant Type</label>
                    <select name="variant_type" 
                            id="edit_variant_type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                            disabled>
                        <option value="size">Size</option>
                        <option value="color">Color</option>
                        <option value="scent">Scent</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Variant type cannot be changed</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Variant Value</label>
                    <input type="text" 
                           name="variant_value" 
                           id="edit_variant_value"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                           required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">SKU Suffix</label>
                    <input type="text" 
                           name="sku_suffix" 
                           id="edit_sku_suffix"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                           required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Price (LKR)</label>
                    <input type="number" 
                           name="price" 
                           id="edit_price"
                           step="0.01" 
                           min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                           required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Discount Price (LKR)</label>
                    <input type="number" 
                        id="edit_discount_price"
                        name="discount_price" 
                        step="0.01" 
                        min="0"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                        placeholder="Leave empty if no discount">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Cost Price (LKR)</label>
                    <input type="number" 
                           name="cost_price" 
                           id="edit_cost_price"
                           step="0.01" 
                           min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                           required>
                </div>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               id="edit_is_active"
                               value="1" 
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeEditVariantModal()" 
                            class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Update Variant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Stock Modal -->
<div id="updateStockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Update Stock</h3>
            <form id="updateStockForm">
                @csrf
                <input type="hidden" id="combination_id" name="combination_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Current Stock</label>
                    <input type="number" 
                           name="current_stock" 
                           id="current_stock"
                           min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                           required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Low Stock Threshold</label>
                    <input type="number" 
                           name="low_stock_threshold" 
                           id="low_stock_threshold"
                           min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                           required>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeUpdateStockModal()" 
                            class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                        Update Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>