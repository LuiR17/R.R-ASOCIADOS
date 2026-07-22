<div class="min-h-screen bg-surface-container-low flex flex-col">
    <!-- Admin Top Header -->
    <header class="bg-surface-container-lowest border-b border-outline-variant sticky top-0 z-30 shadow-sm">
        <div class="max-w-[1400px] mx-auto px-margin-mobile md:px-margin-desktop py-md flex justify-between items-center">
            <div class="flex items-center gap-md">
                <a href="{{ route('catalog') }}" class="font-headline-md text-xl font-bold text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-2xl">admin_panel_settings</span>
                    <span>R.R Y ASOCIADOS <span class="text-xs text-secondary font-normal font-mono-data">| Admin</span></span>
                </a>
            </div>

            <div class="flex items-center gap-md">
                <span class="text-xs font-mono-data text-on-surface-variant hidden sm:inline">
                    {{ Auth::user()->name ?? 'Administrador' }}
                </span>
                <a href="{{ route('catalog') }}" target="_blank" class="px-3 py-1.5 bg-surface-container-highest text-on-surface text-xs font-bold rounded-lg hover:bg-outline-variant transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">visibility</span> Ver Sitio
                </a>
                <button wire:click="logout" class="px-3 py-1.5 bg-secondary text-on-error text-xs font-bold rounded-lg hover:bg-secondary-container transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">logout</span> Salir
                </button>
            </div>
        </div>
    </header>

    <!-- Main Admin Container -->
    <main class="flex-1 max-w-[1400px] w-full mx-auto p-margin-mobile md:p-lg">
        <!-- Flash Message -->
        @if (session()->has('message'))
            <div class="mb-md p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-lg text-sm flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>{{ session('message') }}</span>
                </div>
            </div>
        @endif

        <!-- Action Bar & Metrics -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-md mb-lg">
            <div>
                <h1 class="font-headline-lg text-2xl font-bold text-on-surface">Gestión de Productos y Servicios</h1>
                <p class="text-on-surface-variant text-xs mt-1">Administra el catálogo digital, imágenes, especificaciones técnicas y servicios de reparación.</p>
            </div>

            <button 
                wire:click="createProduct" 
                class="px-5 py-2.5 bg-primary text-on-primary font-bold text-sm rounded-lg hover:bg-primary-container transition-all flex items-center gap-2 shadow-md active:scale-95"
            >
                <span class="material-symbols-outlined">add</span> Nuevo Componente
            </button>
        </div>

        <!-- Filter Controls -->
        <div class="bg-surface-container-lowest p-md border border-outline-variant rounded-xl mb-lg flex flex-col sm:flex-row gap-md justify-between items-center">
            <div class="relative w-full sm:w-80">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Buscar por nombre..." 
                    class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary"
                >
            </div>

            <div class="w-full sm:w-64">
                <select wire:model.live="filterCategory" class="w-full py-2 px-3 bg-surface-container-low border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary">
                    <option value="">Todas las Categorías</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-on-surface">
                    <thead class="bg-surface-container-low border-b border-outline-variant font-label-caps text-xs text-outline uppercase">
                        <tr>
                            <th class="py-3 px-4">Imagen</th>
                            <th class="py-3 px-4">Producto / Servicio</th>
                            <th class="py-3 px-4">Categoría</th>
                            <th class="py-3 px-4">Etiqueta / Specs</th>
                            <th class="py-3 px-4">Estado</th>
                            <th class="py-3 px-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($products as $prod)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="py-3 px-4">
                                    <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="w-12 h-12 rounded object-cover border border-outline-variant bg-surface-container">
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-primary">{{ $prod->name }}</div>
                                    <div class="text-xs text-on-surface-variant truncate max-w-xs">{{ $prod->short_description ?? $prod->description }}</div>
                                </td>
                                <td class="py-3 px-4 text-xs font-mono-data text-outline">
                                    {{ $prod->category->name ?? 'Sin categoría' }}
                                    @if($prod->is_service)
                                        <span class="block text-[10px] text-secondary font-bold">Servicio Reparación</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if($prod->badge)
                                        <span class="px-2 py-0.5 bg-secondary/10 text-secondary border border-secondary/30 rounded text-[11px] font-bold">
                                            {{ $prod->badge }}
                                        </span>
                                    @endif
                                    @if(!empty($prod->specs) && is_array($prod->specs))
                                        <div class="text-[11px] text-outline mt-1 font-mono-data">
                                            {{ count($prod->specs) }} especificaciones
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <button 
                                        wire:click="toggleActive({{ $prod->id }})" 
                                        class="px-2.5 py-1 rounded-full text-xs font-bold transition-all {{ $prod->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}"
                                    >
                                        {{ $prod->is_active ? 'Activo' : 'Inactivo' }}
                                    </button>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button 
                                            wire:click="editProduct({{ $prod->id }})" 
                                            class="p-1.5 text-primary hover:bg-primary/10 rounded transition-colors"
                                            title="Editar"
                                        >
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </button>
                                        <button 
                                            wire:click="confirmDelete({{ $prod->id }})" 
                                            class="p-1.5 text-red-600 hover:bg-red-50 rounded transition-colors"
                                            title="Eliminar"
                                        >
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-lg text-center text-on-surface-variant text-sm">
                                    No hay productos cargados en esta vista.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-md border-t border-outline-variant">
                {{ $products->links() }}
            </div>
        </div>
    </main>

    <!-- Create / Edit Modal -->
    @if($showFormModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm overflow-y-auto">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl max-w-2xl w-full p-lg shadow-2xl relative my-8">
                <div class="flex justify-between items-center mb-md border-b border-outline-variant pb-3">
                    <h2 class="font-headline-md text-xl font-bold text-primary">
                        {{ $productId ? 'Editar Componente' : 'Nuevo Componente / Servicio' }}
                    </h2>
                    <button wire:click="$set('showFormModal', false)" class="text-outline hover:text-on-surface">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>

                <form wire:submit.prevent="saveProduct" class="space-y-md">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-md">
                        <div>
                            <label class="block text-xs font-bold text-on-surface mb-1">Nombre del Producto *</label>
                            <input type="text" wire:model="name" class="w-full p-2 bg-surface-container-low border border-outline-variant rounded text-sm focus:ring-primary">
                            @error('name') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-on-surface mb-1">Categoría *</label>
                            <select wire:model="category_id" class="w-full p-2 bg-surface-container-low border border-outline-variant rounded text-sm focus:ring-primary">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1">Descripción Corta</label>
                        <input type="text" wire:model="short_description" placeholder="Resumen breve para tarjetas..." class="w-full p-2 bg-surface-container-low border border-outline-variant rounded text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1">Descripción Completa</label>
                        <textarea wire:model="description" rows="3" placeholder="Detalles de aplicación y construcción..." class="w-full p-2 bg-surface-container-low border border-outline-variant rounded text-sm"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-md">
                        <div>
                            <label class="block text-xs font-bold text-on-surface mb-1">Badge / Tag (ej: Best Seller, Heavy Duty)</label>
                            <input type="text" wire:model="badge" class="w-full p-2 bg-surface-container-low border border-outline-variant rounded text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-on-surface mb-1">WhatsApp Personalizado (Opcional)</label>
                            <input type="text" wire:model="whatsapp_number" placeholder="593984192262" class="w-full p-2 bg-surface-container-low border border-outline-variant rounded text-sm">
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="p-3 bg-surface-container-low border border-outline-variant/60 rounded-lg">
                        <label class="block text-xs font-bold text-on-surface mb-2">Imagen del Producto</label>
                        <div class="flex flex-col sm:flex-row gap-md items-center">
                            <input type="file" wire:model="image" accept="image/*" class="text-xs text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary file:text-on-primary hover:file:bg-primary-container cursor-pointer">
                            <span class="text-xs text-outline font-bold">O URL directa:</span>
                            <input type="text" wire:model="external_image_url" placeholder="https://..." class="flex-1 p-2 bg-surface-container-lowest border border-outline-variant rounded text-xs">
                        </div>
                    </div>

                    <!-- Dynamic Technical Specs -->
                    <div class="p-3 bg-surface-container-low border border-outline-variant/60 rounded-lg">
                        <label class="block text-xs font-bold text-on-surface mb-2">Especificaciones Técnicas (Presión, Flujo, Torque...)</label>
                        
                        <div class="flex gap-2 mb-2">
                            <input type="text" wire:model="newSpecKey" placeholder="Parámetro (ej: Presión Máx)" class="flex-1 p-2 bg-surface-container-lowest border border-outline-variant rounded text-xs">
                            <input type="text" wire:model="newSpecVal" placeholder="Valor (ej: 3000 PSI)" class="flex-1 p-2 bg-surface-container-lowest border border-outline-variant rounded text-xs">
                            <button type="button" wire:click="addSpec" class="px-3 py-2 bg-primary text-on-primary rounded text-xs font-bold">Agregar</button>
                        </div>

                        @if(!empty($specs))
                            <div class="space-y-1 mt-2">
                                @foreach($specs as $k => $v)
                                    <div class="flex justify-between items-center bg-surface-container-lowest p-2 rounded text-xs border border-outline-variant/40">
                                        <span><strong class="text-primary">{{ $k }}:</strong> {{ $v }}</span>
                                        <button type="button" wire:click="removeSpec('{{ $k }}')" class="text-red-600 hover:text-red-800">
                                            <span class="material-symbols-outlined text-[16px]">close</span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Toggles -->
                    <div class="flex flex-wrap gap-md">
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-on-surface">
                            <input type="checkbox" wire:model="is_active" class="rounded text-primary focus:ring-primary">
                            <span>Producto Activo</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-on-surface">
                            <input type="checkbox" wire:model="is_featured" class="rounded text-primary focus:ring-primary">
                            <span>Destacado</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-on-surface">
                            <input type="checkbox" wire:model="is_service" class="rounded text-primary focus:ring-primary">
                            <span>Es Servicio de Reparación</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-2 pt-md border-t border-outline-variant">
                        <button type="button" wire:click="$set('showFormModal', false)" class="px-4 py-2 bg-surface-container-highest text-on-surface font-bold text-xs rounded-lg">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2 bg-primary text-on-primary font-bold text-xs rounded-lg shadow-md hover:bg-primary-container">
                            Guardar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl max-w-md w-full p-lg shadow-2xl">
                <h3 class="text-lg font-bold text-on-surface mb-2">Confirmar Eliminación</h3>
                <p class="text-on-surface-variant text-xs mb-md">¿Estás seguro de que deseas eliminar este componente del catálogo? Esta acción no se puede deshacer.</p>

                <div class="flex justify-end gap-2">
                    <button wire:click="$set('showDeleteModal', false)" class="px-4 py-2 bg-surface-container-highest text-on-surface text-xs font-bold rounded-lg">
                        Cancelar
                    </button>
                    <button wire:click="deleteProduct" class="px-4 py-2 bg-secondary text-on-error text-xs font-bold rounded-lg shadow-md">
                        Sí, Eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
