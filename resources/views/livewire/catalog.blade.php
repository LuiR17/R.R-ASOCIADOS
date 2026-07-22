<div>
    <!-- Top Contact Announcement Bar -->
    <div class="bg-primary text-on-primary py-2 px-margin-mobile md:px-margin-desktop text-xs font-mono-data border-b border-primary-container">
        <div class="max-w-[1400px] mx-auto flex justify-between items-center gap-2">
            <div class="flex items-center gap-4 flex-wrap">
                <span class="flex items-center gap-1 font-bold">
                    <span class="material-symbols-outlined text-[16px]">phone_iphone</span>
                    Atención / WhatsApp: 095 885 7369
                </span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-[11px] text-tertiary-fixed-dim hidden sm:inline">R.R Y Asociados | Hidraulic</span>
            </div>
        </div>
    </div>

    <!-- TopNavBar -->
    <header class="w-full top-0 sticky bg-surface-container-lowest border-b border-outline-variant shadow-sm z-40">
        <div class="flex justify-between items-center px-margin-mobile md:px-margin-desktop py-md max-w-[1400px] mx-auto w-full gap-md">
            <div class="flex items-center gap-md cursor-pointer" wire:click="resetFilters">
                <div class="flex flex-col">
                    <span class="font-headline-md text-headline-md font-extrabold text-primary tracking-tight">R.R & ASOCIADOS</span>
                    <span class="font-label-caps text-[10px] tracking-widest text-secondary font-bold uppercase">| HIDRAULIC</span>
                </div>
            </div>
            
            <div class="flex-1 max-w-xl hidden md:block">
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Buscar componentes técnicos, bombas, válvulas, PSI..." 
                        class="w-full pl-10 pr-10 py-2 bg-surface-container-low border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all font-body-sm text-on-surface"
                    >
                    @if(!empty($search))
                        <button wire:click="$set('search', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-primary">
                            <span class="material-symbols-outlined text-[18px]">close</span>
                        </button>
                    @endif
                    <!-- Search Loading Indicator -->
                    <div wire:loading wire:target="search" class="absolute right-3 top-1/2 -translate-y-1/2">
                        <span class="animate-spin material-symbols-outlined text-primary text-[18px]">progress_activity</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-md">
                <a href="https://wa.me/593958857369?text=Hola%20R.R%20Y%20Asociados,%20deseo%20mayor%20informaci%C3%B3n%20sobre%20sus%20productos." target="_blank" class="hidden sm:flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-sm transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">chat</span>
                    <span>Contactar WhatsApp</span>
                </a>
            </div>
        </div>

        <!-- Mobile Search Field -->
        <div class="p-md md:hidden border-t border-outline-variant bg-surface-container-low">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Buscar componentes técnicos..." 
                    class="w-full pl-10 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm"
                >
                <div wire:loading wire:target="search" class="absolute right-3 top-1/2 -translate-y-1/2">
                    <span class="animate-spin material-symbols-outlined text-primary text-[18px]">progress_activity</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Global Livewire Loading Overlay Bar -->
    <div wire:loading class="w-full bg-primary/10 border-b border-primary/20 py-1 px-4 text-center text-xs text-primary font-bold animate-pulse">
        <span class="inline-flex items-center gap-1">
            <span class="animate-spin material-symbols-outlined text-[14px]">progress_activity</span> Actualizando catálogo...
        </span>
    </div>

    <!-- Main Container -->
    <div class="flex max-w-[1400px] mx-auto min-h-[calc(100vh-140px)]">
        <!-- SideNavBar -->
        <aside class="h-[calc(100vh-120px)] w-72 sticky top-20 bg-surface-container-low border-r border-outline-variant flex flex-col gap-md p-md hidden md:flex overflow-y-auto">
            <div class="mb-sm">
                <h2 class="font-headline-md text-headline-md font-bold text-primary">Filtros de Catálogo</h2>
                <p class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest mt-1">Secciones Técnicas</p>
            </div>

            <nav class="flex flex-col gap-1.5">
                <button 
                    wire:click="selectCategory(null)" 
                    class="flex items-center justify-between px-4 py-3 rounded-lg font-bold transition-all duration-200 text-left {{ is_null($selectedCategory) ? 'bg-primary-container text-on-primary-container shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-highest' }}"
                >
                    <span class="flex items-center gap-3">
                        <span class="material-symbols-outlined">grid_view</span>
                        <span class="font-label-caps text-label-caps text-sm">Todos los Productos</span>
                    </span>
                    <span class="text-xs opacity-75">({{ \App\Models\Product::where('is_service', false)->where('is_active', true)->count() }})</span>
                </button>

                @foreach($this->categories as $cat)
                    <button 
                        wire:click="selectCategory({{ $cat->id }})" 
                        class="flex items-center justify-between px-4 py-2.5 rounded-lg text-sm transition-all duration-200 text-left {{ $selectedCategory === $cat->id ? 'bg-primary-container text-on-primary-container font-bold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-highest' }}"
                    >
                        <span class="flex items-center gap-3 truncate">
                            <span class="material-symbols-outlined text-[20px]">{{ $cat->icon }}</span>
                            <span class="truncate font-body-sm">{{ $cat->name }}</span>
                        </span>
                        <span class="text-xs opacity-70">({{ $cat->products_count }})</span>
                    </button>
                @endforeach
            </nav>

            <div class="mt-auto border-t border-outline-variant pt-md flex flex-col gap-2">
                @if(!is_null($selectedCategory) || !empty($search) || !empty($selectedBadge))
                    <button wire:click="resetFilters" class="w-full bg-surface-container-highest text-on-surface-variant font-bold py-2 rounded-lg text-xs hover:bg-outline-variant transition-all flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">restart_alt</span> Limpiar Filtros
                    </button>
                @endif

                <a 
                    href="https://wa.me/593958857369?text=Hola%20R.R%20Y%20Asociados,%20deseo%20solicitar%20una%20cotizaci%C3%B3n%20general." 
                    target="_blank"
                    class="w-full bg-secondary text-on-error font-bold py-3 rounded-lg flex items-center justify-center gap-2 hover:bg-secondary-container transition-all active:scale-95 text-sm shadow-md"
                >
                    <span class="material-symbols-outlined">description</span>
                    Solicitar Cotización
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-margin-mobile md:p-lg min-w-0">
            <!-- Header Section -->
            <section class="mb-xl">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-md">
                    <div>
                        <span class="text-xs font-mono-data text-secondary uppercase font-bold tracking-wider">Catálogo Técnico Digital</span>
                        <h1 class="font-headline-lg text-headline-lg text-on-surface mb-2 font-extrabold">{{ $currentCategoryName }}</h1>
                        <p class="text-on-surface-variant font-body-md max-w-3xl">
                            Sistemas hidráulicos de ingeniería de precisión para aplicaciones industriales pesadas y equipo móvil. Distribución y soporte técnico especializado por R.R Y Asociados | Hidraulic.
                        </p>
                    </div>
                </div>

                <!-- Active Filters Notification Bar -->
                @if(!empty($search) || !is_null($selectedCategory) || !empty($selectedBadge))
                    <div class="mt-md p-3 bg-surface-container-low border border-primary/20 rounded-lg flex items-center justify-between text-xs text-on-surface-variant">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-bold text-primary">Filtros Activos:</span>
                            @if(!empty($search))
                                <span class="bg-surface-container-highest px-2 py-1 rounded">Búsqueda: "{{ $search }}"</span>
                            @endif
                            @if(!is_null($selectedCategory))
                                <span class="bg-surface-container-highest px-2 py-1 rounded">Categoría: {{ $currentCategoryName }}</span>
                            @endif
                            @if(!empty($selectedBadge))
                                <span class="bg-surface-container-highest px-2 py-1 rounded">Tag: {{ $selectedBadge }}</span>
                            @endif
                        </div>
                        <button wire:click="resetFilters" class="text-secondary font-bold hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">close</span> Limpiar todo
                        </button>
                    </div>
                @endif
            </section>

            <!-- Product Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-lg mb-xl transition-opacity duration-200" wire:loading.class="opacity-50">
                    @foreach($products as $product)
                        <article class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden group hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                            <div>
                                <div class="relative h-64 bg-surface-container overflow-hidden">
                                    <img 
                                        src="{{ $product->image_url }}" 
                                        alt="{{ $product->name }}" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        loading="lazy"
                                    >
                                    <div class="absolute top-4 left-0 bg-primary w-1.5 h-8 rounded-r-full"></div>
                                    @if($product->badge)
                                        <span class="absolute top-4 right-4 bg-secondary text-on-error px-2 py-1 font-label-caps text-[10px] rounded uppercase font-bold tracking-wider shadow-sm">
                                            {{ $product->badge }}
                                        </span>
                                    @endif
                                </div>

                                <div class="p-md">
                                    <span class="text-[11px] font-mono-data text-outline uppercase tracking-wider block mb-1">
                                        {{ $product->category->name ?? 'General' }}
                                    </span>
                                    <h3 
                                        wire:click="openModal({{ $product->id }})"
                                        class="font-headline-md text-headline-md mb-2 text-primary group-hover:text-primary-container transition-colors cursor-pointer line-clamp-2"
                                    >
                                        {{ $product->name }}
                                    </h3>
                                    <p class="text-on-surface-variant font-body-sm mb-4 line-clamp-2">
                                        {{ $product->short_description ?? $product->description }}
                                    </p>

                                    <!-- Technical Specs Box -->
                                    @if(!empty($product->specs) && is_array($product->specs))
                                        <div class="grid grid-cols-2 gap-2 mb-md bg-surface-container-low p-2.5 rounded border border-outline-variant/40">
                                            @foreach(array_slice($product->specs, 0, 4) as $key => $val)
                                                <div class="flex flex-col">
                                                    <span class="font-label-caps text-[10px] text-outline uppercase tracking-wider truncate">{{ $key }}</span>
                                                    <span class="font-mono-data text-mono-data text-primary font-bold text-xs truncate">{{ $val }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="p-md pt-0 mt-auto flex flex-col gap-2">
                                <a 
                                    href="{{ $product->whatsapp_url }}" 
                                    target="_blank"
                                    class="w-full py-3 bg-secondary text-on-error font-bold rounded hover:bg-secondary-container transition-all flex items-center justify-center gap-2 text-sm shadow-sm active:scale-95"
                                >
                                    <span class="material-symbols-outlined text-[18px]">chat</span>
                                    SOLICITAR COTIZACIÓN
                                </a>
                                <button 
                                    wire:click="openModal({{ $product->id }})" 
                                    class="w-full py-2 bg-surface-container-low text-primary font-bold text-xs rounded hover:bg-surface-container-highest transition-colors flex items-center justify-center gap-1"
                                >
                                    <span class="material-symbols-outlined text-[14px]">visibility</span> Ver Ficha Técnica
                                </button>
                            </div>
                        </article>
                    @endforeach

                    <!-- Empty State / Custom Design Card -->
                    <article class="border-2 border-dashed border-outline-variant rounded-lg flex flex-col items-center justify-center p-xl text-center group hover:border-primary transition-colors cursor-pointer bg-surface-container-low min-h-[380px]">
                        <div class="w-16 h-16 rounded-full bg-surface-container-highest flex items-center justify-center mb-md group-hover:bg-primary-container transition-colors">
                            <span class="material-symbols-outlined text-primary text-3xl group-hover:text-on-primary-container">build_circle</span>
                        </div>
                        <h3 class="font-headline-md text-headline-md text-on-surface mb-2">Diseño y Fabricación Personalizada</h3>
                        <p class="text-on-surface-variant font-body-sm px-4">¿No encuentra la bomba o cilindro con sus especificaciones exactas? Diseñamos y adaptamos sistemas hidráulicos a medida.</p>
                        <a 
                            href="https://wa.me/593958857369?text=Hola%20R.R%20Y%20Asociados,%20necesito%20asesor%C3%ADa%20para%20un%20dise%C3%B1o%20hidr%C3%A1ulico%20personalizado." 
                            target="_blank"
                            class="mt-lg text-primary font-bold font-label-caps border-b-2 border-primary hover:text-primary-container hover:border-primary-container transition-all py-1 inline-flex items-center gap-1 text-sm"
                        >
                            CONSULTAR INGENIERÍA <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                    </article>
                </div>
            @else
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-xl text-center my-lg">
                    <span class="material-symbols-outlined text-outline text-5xl mb-2">search_off</span>
                    <h3 class="text-xl font-bold text-on-surface mb-1">No se encontraron productos</h3>
                    <p class="text-on-surface-variant text-sm mb-md">No hay componentes que coincidan con la búsqueda o filtro seleccionado.</p>
                    <button wire:click="resetFilters" class="px-4 py-2 bg-primary text-on-primary rounded font-bold text-xs">
                        Restablecer Filtros
                    </button>
                </div>
            @endif

            <!-- Services Section -->
            <section class="mt-xl pt-xl border-t border-outline-variant">
                <div class="mb-lg">
                    <span class="text-xs font-mono-data text-secondary uppercase font-bold tracking-wider">Taller Especializado</span>
                    <h2 class="font-headline-lg text-headline-lg text-on-surface font-bold">Servicios de Reparación y Mantenimiento</h2>
                    <p class="text-on-surface-variant text-sm mt-1">Diagnóstico, mantenimiento preventivo y reparación certificada en banco de pruebas para todo tipo de componentes hidráulicos.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-lg">
                    @foreach($services as $service)
                        <article class="bg-surface-container-low border border-primary/20 rounded-lg p-md flex flex-col justify-between text-center group hover:shadow-lg transition-all">
                            <div>
                                <div class="w-16 h-16 rounded-full bg-primary-container flex items-center justify-center mb-md mx-auto group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-on-primary-container text-3xl">
                                        {{ $service->category->icon ?? 'settings_suggest' }}
                                    </span>
                                </div>
                                <h3 class="font-headline-md text-headline-md mb-2 text-primary font-bold">{{ $service->name }}</h3>
                                <p class="text-on-surface-variant font-body-sm mb-lg">{{ $service->description ?? $service->short_description }}</p>
                            </div>

                            <a 
                                href="{{ $service->whatsapp_url }}" 
                                target="_blank"
                                class="mt-auto w-full py-2.5 bg-secondary text-on-error font-bold rounded hover:bg-secondary-container transition-colors flex items-center justify-center gap-2 text-sm shadow-sm"
                            >
                                <span class="material-symbols-outlined text-[18px]">build</span>
                                SOLICITAR REPARACIÓN
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>
        </main>
    </div>

    <!-- Product Detail Modal -->
    @if($showModal && $activeProductModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-lg shadow-2xl relative">
                <button 
                    wire:click="closeModal" 
                    class="absolute top-4 right-4 text-outline hover:text-on-surface p-2 rounded-full hover:bg-surface-container-highest transition-colors"
                >
                    <span class="material-symbols-outlined text-2xl">close</span>
                </button>

                <div class="flex flex-col md:flex-row gap-lg">
                    <div class="w-full md:w-1/2 h-64 md:h-auto rounded-lg overflow-hidden bg-surface-container">
                        <img src="{{ $activeProductModal->image_url }}" alt="{{ $activeProductModal->name }}" class="w-full h-full object-cover">
                    </div>

                    <div class="w-full md:w-1/2 flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-mono-data text-secondary uppercase font-bold tracking-wider">
                                {{ $activeProductModal->category->name ?? 'Componente Hidráulico' }}
                            </span>
                            <h2 class="font-headline-md text-2xl font-bold text-primary mb-2">{{ $activeProductModal->name }}</h2>
                            <p class="text-on-surface-variant text-sm mb-4">{{ $activeProductModal->description }}</p>

                            @if(!empty($activeProductModal->specs) && is_array($activeProductModal->specs))
                                <div class="mb-4">
                                    <h4 class="text-xs font-bold uppercase text-outline mb-2 tracking-wider">Especificaciones Técnicas</h4>
                                    <div class="space-y-1 bg-surface-container-low p-3 rounded border border-outline-variant/40">
                                        @foreach($activeProductModal->specs as $k => $v)
                                            <div class="flex justify-between text-xs py-1 border-b border-outline-variant/20 last:border-0">
                                                <span class="text-on-surface-variant font-medium">{{ $k }}:</span>
                                                <span class="font-bold text-primary font-mono-data">{{ $v }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <a 
                            href="{{ $activeProductModal->whatsapp_url }}" 
                            target="_blank" 
                            class="w-full py-3 bg-secondary text-on-error font-bold rounded-lg hover:bg-secondary-container transition-all flex items-center justify-center gap-2 text-sm shadow-md"
                        >
                            <span class="material-symbols-outlined">chat</span> SOLICITAR COTIZACIÓN VÍA WHATSAPP
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Footer -->
    <footer class="w-full mt-xl bg-tertiary border-t border-outline">
        <div class="flex flex-col md:flex-row justify-between items-start px-margin-mobile md:px-margin-desktop py-xl w-full max-w-[1400px] mx-auto gap-xl">
            <div class="flex flex-col gap-3 max-w-sm">
                <span class="font-headline-md text-headline-md text-on-tertiary font-bold">R.R Y Asociados | Hidraulic</span>
                <p class="font-body-sm text-tertiary-fixed-dim text-xs leading-relaxed">
                    Ingeniería y mantenimiento de bombas, válvulas, cilindros y componentes oleohidráulicos industriales y móviles.
                </p>
                <p class="font-mono-data text-xs text-tertiary-fixed-dim">© {{ date('Y') }} R.R Y Asociados | Hidraulic. Todos los derechos reservados.</p>
            </div>

            <div class="flex flex-col gap-2 text-xs text-tertiary-fixed-dim">
                <h4 class="font-bold text-white uppercase tracking-wider mb-1 text-sm">Contacto</h4>
                <p class="flex items-center gap-2 text-white font-bold"><span class="material-symbols-outlined text-[18px] text-emerald-400">phone_iphone</span> WhatsApp / Teléfono: 095 885 7369</p>
            </div>
        </div>
    </footer>
</div>
