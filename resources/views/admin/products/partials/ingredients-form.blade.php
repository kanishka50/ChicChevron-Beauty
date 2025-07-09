<!-- Ingredients Management -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Product Ingredients</h2>
    <p class="text-sm text-gray-600 mb-4">Add ingredients to make your product searchable by ingredient filtering.</p>
    
    <div id="ingredients-container">
        @if(isset($product) && $product->ingredients->isNotEmpty())
            @foreach($product->ingredients as $index => $ingredient)
                <div class="ingredient-row mb-3">
                    <div class="flex gap-2">
                        <input type="text" 
                               name="ingredients[]" 
                               value="{{ old('ingredients.' . $index, $ingredient->ingredient_name) }}"
                               placeholder="Enter ingredient name (e.g., Hyaluronic Acid, Vitamin E)"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="button" 
                                onclick="removeIngredient(this)" 
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded {{ $loop->first && $product->ingredients->count() == 1 ? 'hidden' : '' }}"
                                title="Remove ingredient">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="ingredient-row mb-3">
                <div class="flex gap-2">
                    <input type="text" 
                           name="ingredients[]" 
                           value="{{ old('ingredients.0') }}"
                           placeholder="Enter ingredient name (e.g., Hyaluronic Acid, Vitamin E)"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button" 
                            onclick="removeIngredient(this)" 
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded hidden"
                            title="Remove ingredient">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>
    
    <div class="flex justify-between items-center mt-4">
        <button type="button" 
                onclick="addIngredient()" 
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Ingredient
        </button>
        
        <div class="text-sm text-gray-500">
            <span id="ingredient-count">{{ isset($product) ? $product->ingredients->count() : 1 }}</span> ingredient(s) added
        </div>
    </div>

    <!-- Common Ingredients Suggestions -->
    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
        <h4 class="text-sm font-medium text-gray-700 mb-3">Common Beauty Ingredients:</h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            @php
                $commonIngredients = [
                    'Hyaluronic Acid', 'Vitamin E', 'Vitamin C', 'Retinol', 
                    'Niacinamide', 'Salicylic Acid', 'Glycolic Acid', 'Peptides',
                    'Collagen', 'Ceramides', 'Shea Butter', 'Argan Oil',
                    'Jojoba Oil', 'Aloe Vera', 'Green Tea', 'Chamomile',
                    'Zinc Oxide', 'Titanium Dioxide', 'Kojic Acid', 'Alpha Arbutin'
                ];
            @endphp
            
            @foreach($commonIngredients as $ingredient)
                <button type="button" 
                        onclick="addSuggestedIngredient('{{ $ingredient }}')"
                        class="text-xs bg-white hover:bg-blue-50 border border-gray-200 hover:border-blue-300 text-gray-700 px-2 py-1 rounded transition-colors">
                    {{ $ingredient }}
                </button>
            @endforeach
        </div>
        <p class="text-xs text-gray-500 mt-2">Click any ingredient to add it quickly</p>
    </div>

    <!-- Ingredients Help -->
    <div class="mt-4 p-3 bg-blue-50 rounded-lg">
        <h4 class="text-sm font-medium text-blue-900 mb-2">Why add ingredients?</h4>
        <ul class="text-sm text-blue-800 space-y-1">
            <li>• Customers can search for products by specific ingredients</li>
            <li>• Enable "include/exclude ingredient" filtering</li>
            <li>• Help customers with allergies or specific skin needs</li>
            <li>• Improve product discoverability</li>
        </ul>
    </div>
</div>

@push('scripts')
<script>
    // Ingredients management functions
    function addIngredient() {
        const container = document.getElementById('ingredients-container');
        const newRow = document.createElement('div');
        newRow.className = 'ingredient-row mb-3';
        newRow.innerHTML = `
            <div class="flex gap-2">
                <input type="text" 
                       name="ingredients[]" 
                       placeholder="Enter ingredient name (e.g., Hyaluronic Acid, Vitamin E)"
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="button" 
                        onclick="removeIngredient(this)" 
                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded"
                        title="Remove ingredient">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        container.appendChild(newRow);
        
        // Focus on the new input
        const newInput = newRow.querySelector('input');
        newInput.focus();
        
        updateIngredientButtons();
        updateIngredientCount();
    }

    function removeIngredient(button) {
        const row = button.closest('.ingredient-row');
        row.remove();
        updateIngredientButtons();
        updateIngredientCount();
    }

    function addSuggestedIngredient(ingredientName) {
        // Check if ingredient already exists
        const existingInputs = document.querySelectorAll('#ingredients-container input[name="ingredients[]"]');
        for (let input of existingInputs) {
            if (input.value.toLowerCase() === ingredientName.toLowerCase()) {
                // Highlight existing ingredient
                input.focus();
                input.classList.add('ring-2', 'ring-yellow-500');
                setTimeout(() => {
                    input.classList.remove('ring-2', 'ring-yellow-500');
                }, 2000);
                return;
            }
        }

        // Find first empty input or add new one
        const emptyInput = Array.from(existingInputs).find(input => input.value.trim() === '');
        if (emptyInput) {
            emptyInput.value = ingredientName;
            emptyInput.focus();
        } else {
            addIngredient();
            // Set value of the newly added input
            const newInput = document.querySelector('#ingredients-container .ingredient-row:last-child input');
            newInput.value = ingredientName;
        }
        
        updateIngredientCount();
    }

    function updateIngredientButtons() {
        const rows = document.querySelectorAll('.ingredient-row');
        rows.forEach((row, index) => {
            const removeBtn = row.querySelector('button[onclick^="removeIngredient"]');
            if (rows.length === 1) {
                removeBtn.classList.add('hidden');
            } else {
                removeBtn.classList.remove('hidden');
            }
        });
    }

    function updateIngredientCount() {
        const inputs = document.querySelectorAll('#ingredients-container input[name="ingredients[]"]');
        const filledInputs = Array.from(inputs).filter(input => input.value.trim() !== '');
        document.getElementById('ingredient-count').textContent = filledInputs.length;
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateIngredientButtons();
        updateIngredientCount();
        
        // Handle old input restoration (for validation errors)
        @if(old('ingredients'))
            const oldIngredients = @json(old('ingredients'));
            const container = document.getElementById('ingredients-container');
            container.innerHTML = '';
            
            oldIngredients.forEach((ingredient, index) => {
                if (ingredient || index === 0) {
                    const newRow = document.createElement('div');
                    newRow.className = 'ingredient-row mb-3';
                    newRow.innerHTML = `
                        <div class="flex gap-2">
                            <input type="text" 
                                   name="ingredients[]" 
                                   value="${ingredient || ''}"
                                   placeholder="Enter ingredient name (e.g., Hyaluronic Acid, Vitamin E)"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="button" 
                                    onclick="removeIngredient(this)" 
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded ${index === 0 && oldIngredients.length === 1 ? 'hidden' : ''}"
                                    title="Remove ingredient">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    `;
                    container.appendChild(newRow);
                }
            });
            updateIngredientButtons();
            updateIngredientCount();
        @endif

        // Add input event listeners to update count in real-time
        document.addEventListener('input', function(e) {
            if (e.target.name === 'ingredients[]') {
                updateIngredientCount();
            }
        });
    });
</script>
@endpush