<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Left Side -->
            <div class="flex items-center">

                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-lg">
                            <span class="text-xl font-bold text-sartu-rojo">S</span>
                        </div>
                        <span class="text-gray-800 dark:text-gray-200 font-bold text-xl">SARTU</span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden sm:flex sm:space-x-8 sm:ml-10">

                    {{-- ADMIN --}}
                    @if(Auth::user()->rol_id === 1)
                    <x-nav-link :href="route('empresa.index')" :active="request()->routeIs('empresa.*')">
                        Empresas
                    </x-nav-link>

                    <x-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')">
                        Usuarios
                    </x-nav-link>

                    <x-nav-link :href="route('auditoria.index')" :active="request()->routeIs('auditoria.*')">
                        Auditoría
                    </x-nav-link>
                    @endif


                    {{-- ENCARGADO --}}
                    @if(Auth::user()->rol_id === 2 )

                    <x-nav-link :href="route('auditoria.index')" :active="request()->routeIs('auditoria.*')">
                        Auditoría
                    </x-nav-link>

                    <x-nav-link :href="route('empresa.estado')" :active="request()->routeIs('empresa.estado')">
                        Estado Empresa
                    </x-nav-link>

                    <x-nav-link :href="route('empresa.resumen')" :active="request()->routeIs('empresa.resumen')">
                        Resumen Empresa
                    </x-nav-link>

                    <x-nav-link :href="route('encargado.empleados')" :active="request()->routeIs('encargado.empleados')">
                        Empleados
                    </x-nav-link>

                    <x-nav-link :href="route('fichajes.create')" :active="request()->routeIs('fichajes.create')">
                        Mi Fichaje
                    </x-nav-link>

                    <x-nav-link :href="route('fichaje.resumen')" :active="request()->routeIs('fichaje.resumen')">
                        Mi Resumen
                    </x-nav-link>

                    @endif


                    {{-- EMPLEADO --}}
                    @if(Auth::user()->rol_id === 3)

                    <x-nav-link :href="route('fichajes.create')" :active="request()->routeIs('fichajes.create')">
                        Fichar
                    </x-nav-link>

                    <x-nav-link :href="route('fichaje.resumen')" :active="request()->routeIs('fichaje.resumen')">
                        Mi Resumen
                    </x-nav-link>

                    @endif


                    {{-- Dashboard para todos --}}
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>

                </div>
            </div>

            <!-- Right: Profile -->
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">

                            {{-- AVATAR --}}
                            <img
                                src="{{ Auth::user()->avatar 
                    ? asset('storage/' . Auth::user()->avatar) 
                    : asset('images/avatar.png') }}"
                                class="w-8 h-8 rounded-full object-cover border"
                                alt="Avatar">

                            {{-- NOMBRE --}}
                            <span>{{ Auth::user()->name }}</span>

                            {{-- FLECHA --}}
                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 
                     10.586l3.293-3.293a1 1 0 111.414 
                     1.414l-4 4a1 1 0 01-1.414 
                     0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>

                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Perfil
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Cerrar sesión
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="sm:hidden flex items-center">
                <button @click="open = !open"
                    class="p-2 rounded-md text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }"
                            class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }"
                            class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- MOBILE MENU -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">

        <div class="pt-2 pb-3 space-y-1">

            {{-- ADMIN --}}
            @if(Auth::user()->rol_id === 1)
            <x-responsive-nav-link :href="route('empresa.index')">Empresas</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('usuarios.index')">Usuarios</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('auditoria.index')">Auditoría</x-responsive-nav-link>
            @endif

            {{-- ENCARGADO --}}
            @if(Auth::user()->rol_id === 2)

            <x-nav-link :href="route('auditoria.index')" :active="request()->routeIs('auditoria.*')">
                Auditoría
            </x-nav-link>

            <x-responsive-nav-link :href="route('empresa.estado')">
                Estado Empresa
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('empresa.resumen')">
                Resumen Empresa
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('encargado.empleados')">
                Empleados
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('fichajes.create')">
                Mi Fichaje
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('fichaje.resumen')">
                Mi Resumen
            </x-responsive-nav-link>

            @endif

            {{-- EMPLEADO --}}
            @if(Auth::user()->rol_id === 3)
            <x-responsive-nav-link :href="route('fichajes.create')">
                Fichar
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('fichaje.resumen')">
                Mi Resumen
            </x-responsive-nav-link>
            @endif

            {{-- Dashboard para todos --}}
            <x-responsive-nav-link :href="route('dashboard')">
                Dashboard
            </x-responsive-nav-link>

        </div>

        <!-- Mobile Profile -->
        <div class="pt-4 pb-1 border-t border-gray-300">
            <div class="px-4">
                <div class="font-medium text-base">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Perfil
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Cerrar sesión
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
