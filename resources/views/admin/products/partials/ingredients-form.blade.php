<!-- Ingredients Management -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-2">Product Ingredients</h2>
        <p class="text-sm text-gray-600">Add ingredients to make your product searchable by ingredient filtering.</p>
    </div>
    
    <div id="ingredients-container" class="space-y-3">
        @if(isset($product) && $product->ingredients->isNotEmpty())
            @foreach($product->ingredients as $index => $ingredient)
                <div class="ingredient-row">
                    <div class="flex gap-3 items-start">
                        <div class="flex-1">
                            <input type="text" 
                                   name="ingredients[]" 
                                   value="{{ old('ingredients.' . $index, $ingredient->ingredient_name) }}"
                                   placeholder="Enter ingredient name (e.g., Hyaluronic Acid, Vitamin E)"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <button type="button" 
                                onclick="removeIngredient(this)" 
                                class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md transition-colors {{ $loop->first && $product->ingredients->count() == 1 ? 'hidden' : '' }}"
                                title="Remove ingredient">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="ingredient-row">
                <div class="flex gap-3 items-start">
                    <div class="flex-1">
                        <input type="text" 
                               name="ingredients[]" 
                               value="{{ old('ingredients.0') }}"
                               placeholder="Enter ingredient name (e.g., Hyaluronic Acid, Vitamin E)"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <button type="button" 
                            onclick="removeIngredient(this)" 
                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md transition-colors hidden"
                            title="Remove ingredient">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>
    
    <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
        <button type="button" 
                onclick="addIngredient()" 
                class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-md transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Ingredient
        </button>
        
        <div class="text-sm text-gray-600">
            <span id="ingredient-count" class="font-medium">{{ isset($product) ? $product->ingredients->count() : 1 }}</span> ingredient(s) added
        </div>
    </div>

    <!-- Common Ingredients Suggestions -->
    <div class="mt-6 bg-gray-50 rounded-lg p-4">
        <h4 class="text-sm font-medium text-gray-700 mb-3">Quick Add Common Ingredients:</h4>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2">
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
                        class="text-xs bg-white hover:bg-blue-50 border border-gray-300 hover:border-blue-400 text-gray-700 hover:text-blue-700 px-3 py-1.5 rounded-md transition-all duration-150">
                    {{ $ingredient }}
                </button>
            @endforeach
        </div>
        <p class="text-xs text-gray-500 mt-3">Click any ingredient above to add it quickly to your product</p>
    </div>

    <!-- Ingredients Help -->
    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-blue-900">Why add ingredients?</h4>
                <ul class="mt-2 text-sm text-blue-800 space-y-1">
                    <li>• Customers can search for products by specific ingredients</li>
                    <li>• Enable "include/exclude ingredient" filtering</li>
                    <li>• Help customers with allergies or specific skin needs</li>
                    <li>• Improve product discoverability</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Ingredients management functions
    function addIngredient() {
        const container = document.getElementById('ingredients-container');
        const newRow = document.createElement('div');
        newRow.className = 'ingredient-row';
        newRow.innerHTML = `
            <div class="flex gap-3 items-start">
                <div class="flex-1">
                    <input type="text" 
                           name="ingredients[]" 
                           placeholder="Enter ingredient name (e.g., Hyaluronic Acid, Vitamin E)"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button type="button" 
                        onclick="removeIngredient(this)" 
                        class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md transition-colors"
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
                input.classList.add('ring-2', 'ring-yellow-400', 'bg-yellow-50');
                setTimeout(() => {
                    input.classList.remove('ring-2', 'ring-yellow-400', 'bg-yellow-50');
                }, 2000);
                return;
            }
        }

        // Find first empty input or add new one
        const emptyInput = Array.from(existingInputs).find(input => input.value.trim() === '');
        if (emptyInput) {
            emptyInput.value = ingredientName;
            emptyInput.focus();
            emptyInput.classList.add('ring-2', 'ring-green-400', 'bg-green-50');
            setTimeout(() => {
                emptyInput.classList.remove('ring-2', 'ring-green-400', 'bg-green-50');
            }, 1500);
        } else {
            addIngredient();
            // Set value of the newly added input
            const newInput = document.querySelector('#ingredients-container .ingredient-row:last-child input');
            newInput.value = ingredientName;
            newInput.classList.add('ring-2', 'ring-green-400', 'bg-green-50');
            setTimeout(() => {
                newInput.classList.remove('ring-2', 'ring-green-400', 'bg-green-50');
            }, 1500);
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
                    newRow.className = 'ingredient-row';
                    newRow.innerHTML = `
                        <div class="flex gap-3 items-start">
                            <div class="flex-1">
                                <input type="text" 
                                       name="ingredients[]" 
                                       value="${ingredient || ''}"
                                       placeholder="Enter ingredient name (e.g., Hyaluronic Acid, Vitamin E)"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <button type="button" 
                                    onclick="removeIngredient(this)" 
                                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md transition-colors ${index === 0 && oldIngredients.length === 1 ? 'hidden' : ''}"
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