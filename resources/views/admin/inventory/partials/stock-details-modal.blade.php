
<!-- Stock Details Modal -->
<div id="stockDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-5xl shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Stock Details & FIFO Batches</h3>
                <button onclick="closeStockDetailsModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Product Info -->
            <div id="details_product_info" class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <!-- Product information will be populated here -->
            </div>
            
            <!-- Stock Summary -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="text-sm font-medium text-blue-700 mb-1">Current Stock</div>
                    <div id="details_current_stock" class="text-2xl font-bold text-blue-900">-</div>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                    <div class="text-sm font-medium text-yellow-700 mb-1">Reserved Stock</div>
                    <div id="details_reserved_stock" class="text-2xl font-bold text-yellow-900">-</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="text-sm font-medium text-green-700 mb-1">Available Stock</div>
                    <div id="details_available_stock" class="text-2xl font-bold text-green-900">-</div>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                    <div class="text-sm font-medium text-purple-700 mb-1">Average Cost</div>
                    <div id="details_average_cost" class="text-2xl font-bold text-purple-900">-</div>
                </div>
            </div>
            
            <!-- FIFO Batches -->
            <div class="mb-6">
                <h4 class="text-md font-semibold text-gray-900 mb-3">FIFO Batches (Oldest First)</h4>
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Batch Number</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider text-center">Available Qty</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Cost per Unit</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Total Value</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Received Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider text-center">Age (Days)</th>
                            </tr>
                        </thead>
                        <tbody id="details_batches_table" class="bg-white divide-y divide-gray-200">
                            <!-- Batch data will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Recent Movements -->
            <div class="mb-6">
                <h4 class="text-md font-semibold text-gray-900 mb-3">Recent Stock Movements</h4>
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="max-h-64 overflow-y-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider text-center">Quantity</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Batch</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Reason</th>
                                </tr>
                            </thead>
                            <tbody id="details_movements_table" class="bg-white divide-y divide-gray-200">
                                <!-- Movement data will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button onclick="closeStockDetailsModal()" 
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Close
                </button>
                <button onclick="addStockFromDetails()" 
                        class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    Add Stock
                </button>
                <button onclick="adjustStockFromDetails()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    Adjust Stock
                </button>
            </div>
        </div>
    </div>
</div>