<div class="min-h-screen flex items-center justify-center bg-surface-container-low p-margin-mobile">
    <div class="max-w-md w-full bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-xl p-xl">
        <div class="text-center mb-lg">
            <div class="w-16 h-16 bg-primary-container text-on-primary-container rounded-full flex items-center justify-center mx-auto mb-md shadow-inner">
                <span class="material-symbols-outlined text-3xl">lock</span>
            </div>
            <h1 class="font-headline-md text-2xl font-bold text-primary">Acceso Administrativo</h1>
            <p class="text-on-surface-variant text-xs mt-1">R.R Y Asociados | Hidraulic</p>
        </div>

        @if (session()->has('error'))
            <div class="mb-md p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="login" class="space-y-md">
            <div>
                <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-1">Correo Electrónico</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">mail</span>
                    <input 
                        type="email" 
                        wire:model="email" 
                        placeholder="admin@rryasociados.com"
                        class="w-full pl-10 pr-4 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary"
                    >
                </div>
                @error('email') <span class="text-red-600 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-1">Contraseña</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">key</span>
                    <input 
                        type="password" 
                        wire:model="password" 
                        placeholder="••••••••"
                        class="w-full pl-10 pr-4 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary"
                    >
                </div>
                @error('password') <span class="text-red-600 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-between text-xs text-on-surface-variant">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model="remember" class="rounded border-outline-variant text-primary focus:ring-primary">
                    <span>Recordar sesión</span>
                </label>
            </div>

            <button 
                type="submit" 
                class="w-full py-3 bg-primary text-on-primary font-bold rounded-lg hover:bg-primary-container transition-all flex items-center justify-center gap-2 text-sm shadow-md active:scale-95"
            >
                <span class="material-symbols-outlined">login</span> Iniciar Sesión
            </button>
        </form>

        <div class="mt-lg pt-md border-t border-outline-variant text-center">
            <a href="{{ route('catalog') }}" class="text-xs font-bold text-primary hover:underline inline-flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">arrow_back</span> Volver al Catálogo Público
            </a>
        </div>
    </div>
</div>
