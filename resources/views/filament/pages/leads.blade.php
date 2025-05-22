<x-filament::page>
    <div class="kanban-container flex gap-4" style="width: 100%;">
        @foreach ($categorias as $categoria)
        <div class="kanban-column rounded-lg p-4" id="{{ Str::slug($categoria) }}">
            <h3 class="text-lg font-semibold mb-2">{{ $categoria }}</h3>
            <div class="kanban-items space-y-2" style="height: 100%;">
            @foreach($clientes->where('categoria', $categoria) as $cliente)
                <div class="kanban-item bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-3 cursor-move" 
                     draggable="true" 
                     data-id="{{ $cliente->id }}" 
                     data-categoria="{{ $cliente->categoria }}"
                     wire:key="cliente-{{ $cliente->id }}">
                    <div class="kanban-item-avatar">C{{ $cliente->id }}</div>
                    <div class="kanban-item-content">
                        <div class="kanban-item-name">{{ $cliente->nombre }}</div>
                        <div class="kanban-item-id">Lead #{{ str_pad($cliente->id, 4, '0', STR_PAD_LEFT) }}</div>
                        <div class="kanban-item-price">$500 <span class="kanban-item-no-tasks">no hay tareas</span></div>
                    </div>
                    <div class="kanban-item-date">{{ $cliente->created_at->format('d/m/Y') }}</div>
                </div>
            @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <style>
        .kanban-column {
            min-width: 350px;
            flex: 0 0 auto;
            cursor: grab;
            transition: border 0.2s ease;
            border: 1px solid transparent;
        }

        .kanban-column:active {
            cursor: grabbing;
        }

        .kanban-column.dragover {
            border: 2px dashed #6b7280;
        }

        .kanban-container {
            display: flex;
            gap: 4px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
            scroll-behavior: smooth;
            min-height: 80vh;
        }

        .kanban-container::-webkit-scrollbar {
            display: none;
        }

        .kanban-item {
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
        }

        .kanban-item.dragging {
            opacity: 0.5;
        }

        .kanban-item-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e1e8ed;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #65676b;
        }

        .kanban-item-content {
            flex: 1;
        }

        .kanban-item-name {
            font-size: 0.9rem;
            margin-bottom: 2px;
            font-weight: 500;
        }

        .kanban-item-id {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .kanban-item-date {
            position: absolute;
            top: 0.25rem;
            right: 0.5rem;
            font-size: 0.75rem;
            color: #6b7280;
            padding: 0;
            background: none;
        }

        .kanban-item-status {
            font-size: 0.75rem;
            color: #4CAF50;
            margin-top: 0.25rem;
        }

        .kanban-item-price {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .kanban-item-no-tasks {
            color: #FFA500;
            font-size: 0.75rem;
            margin-left: 0.5rem;
        }
    </style>

    <script>
        // Función para inicializar los eventos de drag and drop
        function initDragAndDrop() {
            let draggedItem = null;
            let startX;
            let scrollLeft;
            let isDown = false;

            const kanbanContainer = document.querySelector('.kanban-container');
            const draggableItems = document.querySelectorAll('.kanban-item');
            
            // Check if the click is on a draggable item
            function isClickOnDraggable(e) {
                return e.target.closest('.kanban-item') !== null;
            }

            // Drag-to-scroll functionality
            kanbanContainer.addEventListener('mousedown', (e) => {
                // Only enable drag-to-scroll if not clicking on a draggable item
                if (isClickOnDraggable(e)) return;
                
                isDown = true;
                startX = e.pageX - kanbanContainer.offsetLeft;
                scrollLeft = kanbanContainer.scrollLeft;
                e.preventDefault();
            });

            kanbanContainer.addEventListener('mouseup', () => {
                isDown = false;
            });

            kanbanContainer.addEventListener('mouseleave', () => {
                isDown = false;
            });

            kanbanContainer.addEventListener('mousemove', (e) => {
                // Only scroll if not over a draggable item
                if (isClickOnDraggable(e)) return;
                
                if (!isDown) return;
                
                e.preventDefault();
                const x = e.pageX - kanbanContainer.offsetLeft;
                const walk = (x - startX) * 2; // Adjust speed
                kanbanContainer.scrollLeft = scrollLeft - walk;
            });

            // Allow items to be dragged
            draggableItems.forEach(item => {
                item.addEventListener('dragstart', function(e) {
                    draggedItem = this;
                    setTimeout(() => this.classList.add('dragging'), 0);
                });

                item.addEventListener('dragend', function() {
                    this.classList.remove('dragging');
                    draggedItem = null;
                });
            });

            // Allow columns to accept dragged items
            document.querySelectorAll('.kanban-items').forEach(column => {
                column.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.closest('.kanban-column').classList.add('dragover');
                });

                column.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    this.closest('.kanban-column').classList.remove('dragover');
                });

                column.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.closest('.kanban-column').classList.remove('dragover');
                    
                    if (!draggedItem) return;

                    const categoriaActual = draggedItem.dataset.categoria;
                    const nuevaCategoria = this.closest('.kanban-column').querySelector('h3').textContent;
                    const idCliente = draggedItem.dataset.id;

                    // Solo actualizamos si la categoría es diferente
                    if (categoriaActual !== nuevaCategoria) {
                        @this.updateCategoria(idCliente, nuevaCategoria)
                            .then(() => {
                                // Reasignar eventos después de la actualización
                                initDragAndDrop();
                            })
                            .catch(error => {
                                console.error('Error al actualizar la categoría:', error);
                            });
                    }
                });
            });
        }

        // Inicializar cuando se carga la página
        document.addEventListener('DOMContentLoaded', initDragAndDrop);

        // Reasignar eventos después de cada actualización de Livewire
        document.addEventListener('livewire:load', initDragAndDrop);
    </script>
</x-filament::page>
