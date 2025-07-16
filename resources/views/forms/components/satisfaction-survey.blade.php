<div class="p-4 border-t dark:border-gray-700">
    <div class="mb-4">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Encuesta de Satisfacción</h3>
        
        <div class="space-y-4">
            <!-- Overall Satisfaction -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Satisfacción General</label>
                <div class="flex items-center space-x-2">
                    @for($i = 1; $i <= 5; $i++)
                        <button wire:click="setRating('overall_satisfaction', {{ $i }})"
                                class="text-yellow-400 hover:text-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </button>
                    @endfor
                </div>
            </div>

            <!-- Service Quality -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Calidad del Servicio</label>
                <div class="flex items-center space-x-2">
                    @for($i = 1; $i <= 5; $i++)
                        <button wire:click="setRating('service_quality', {{ $i }})"
                                class="text-yellow-400 hover:text-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </button>
                    @endfor
                </div>
            </div>

            <!-- Product Quality -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Calidad del Producto</label>
                <div class="flex items-center space-x-2">
                    @for($i = 1; $i <= 5; $i++)
                        <button wire:click="setRating('product_quality', {{ $i }})"
                                class="text-yellow-400 hover:text-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </button>
                    @endfor
                </div>
            </div>

            <!-- Would Recommend -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">¿Recomendaría nuestros servicios?</label>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input type="radio" wire:model="would_recommend" value="1" class="mr-2">
                        <span class="text-sm">Sí</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" wire:model="would_recommend" value="0" class="mr-2">
                        <span class="text-sm">No</span>
                    </label>
                </div>
            </div>

            <!-- Comments -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Comentarios</label>
                <textarea wire:model="comments" rows="3" class="w-full px-3 py-2 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:border-blue-500 dark:focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:focus:ring-blue-500"></textarea>
            </div>

            <!-- Submit Button -->
            <div>
                <button wire:click="submitSurvey" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Enviar Encuesta
                </button>
            </div>
        </div>
    </div>
</div>
