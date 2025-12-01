<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-sartu-white">
            Gestión de Usuarios
        </h2>
    </x-slot>

    <div class="py-6">

        @if(session('success'))
        <div class="bg-green-500 text-white px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        @if(Auth::user()->rol->nombre !== 'empleado')
        <a href="{{ route('usuarios.create') }}"
            class="px-4 py-2 bg-sartu-rojo text-white rounded shadow hover:bg-sartu-rojo-oscuro">
            ➕ Crear Usuario
        </a>
        @endif

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full rounded border border-sartu-gris-oscuro bg-white">
                <thead class="bg-sartu-marron text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Nombre</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2">Rol</th>
                        <th class="px-4 py-2">Empresa</th>
                        <th class="px-4 py-2">Estado</th>
                        <th class="px-4 py-2 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-sartu-gris-oscuro">
                    @foreach ($usuarios as $item)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2">{{ $item->name }}</td>
                        <td class="px-4 py-2">{{ $item->email }}</td>

                        <!-- Protegido contra rol nulo -->
                        <td class="px-4 py-2 text-center">
                            {{ $item->rol->nombre ?? '—' }}
                        </td>

                        <!-- Protegido contra empresa nula -->
                        <td class="px-4 py-2 text-center">
                            {{ $item->empresa->nombre ?? '—' }}
                        </td>

                        <td class="px-4 py-2 text-center">
                            @if($item->estado === 'activo')
                            <span class="px-2 py-1 bg-green-500 text-white rounded text-sm">
                                Activo
                            </span>
                            @elseif($item->estado === 'pendiente')
                            <span class="px-2 py-1 bg-yellow-500 text-white rounded text-sm">
                                Pendiente
                            </span>
                            @elseif($item->estado === 'bloqueado')
                            <span class="px-2 py-1 bg-red-600 text-white rounded text-sm">
                                Bloqueado
                            </span>
                            @endif
                        </td>

                        <td class="px-4 py-2 text-center">
                            @if(Auth::user()->rol->nombre !== 'empleado')

                            <a href="{{ route('usuarios.edit', $item->id) }}"
                                class="text-blue-600 mr-3 hover:underline">
                                Editar
                            </a>

                            {{-- Botón BLOQUEAR solo para admin_sistema y si no está bloqueado --}}
                            @if(Auth::user()->rol->nombre === 'admin_sistema' && $item->estado === 'bloqueado')
                            <form action="{{ route('usuarios.desbloquear', $item->id) }}"
                                method="POST"
                                class="inline-block"
                                onsubmit="return confirm('¿Seguro que deseas desbloquear este usuario? Se generará una nueva contraseña temporal.')">
                                @csrf
                                @method('PATCH')

                                <button class="text-green-600 mr-3 hover:underline">
                                    Desbloquear
                                </button>
                            </form>
                            @endif
                            <form action="{{ route('usuarios.destroy', $item->id) }}"
                                method="POST"
                                class="inline-block"
                                onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">
                                    Eliminar
                                </button>
                            </form>

                            @else
                            —
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
