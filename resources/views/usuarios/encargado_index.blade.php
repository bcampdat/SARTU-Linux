<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Lista de Empleados</h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto space-y-6">

        {{-- FILTROS --}}
        <form method="GET" class="bg-white p-4 rounded shadow grid grid-cols-1 md:grid-cols-5 gap-4">

            <input
                type="text"
                name="nombre"
                placeholder="Buscar por nombre"
                value="{{ request('nombre') }}"
                class="border rounded px-3 py-2 w-full"
            />

            <input
                type="text"
                name="email"
                placeholder="Buscar por email"
                value="{{ request('email') }}"
                class="border rounded px-3 py-2 w-full"
            />

            <select name="rol" class="border rounded px-3 py-2 w-full">
                <option value="">-- Rol --</option>
                <option value="empleado"  @selected(request('rol')=='empleado')>Empleado</option>
                <option value="encargado" @selected(request('rol')=='encargado')>Encargado</option>
            </select>

            {{-- FILTRO POR ESTADO REAL --}}
            <select name="estado" class="border rounded px-3 py-2 w-full">
                <option value="">-- Estado --</option>
                <option value="activo"    @selected(request('estado')==='activo')>Activo</option>
                <option value="pendiente" @selected(request('estado')==='pendiente')>Pendiente</option>
                <option value="bloqueado" @selected(request('estado')==='bloqueado')>Bloqueado</option>
            </select>

            <div class="flex gap-4">
                <button class="bg-sartu-rojo text-white px-4 py-2 rounded">
                    Filtrar
                </button>

                <a href="{{ route('encargado.empleados') }}"
                   class="bg-gray-300 px-4 py-2 rounded">
                    Limpiar
                </a>
            </div>
        </form>

        {{-- TABLA --}}
        <div class="bg-white shadow rounded p-6">

            <table class="min-w-full border text-sm">
                <thead class="bg-sartu-rojo text-white">
                    <tr>
                        <th class="px-4 py-2">Nombre</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Rol</th>
                        <th class="px-4 py-2">Estado</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($empleados as $emp)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $emp->name }}</td>
                        <td class="px-4 py-2">{{ $emp->email }}</td>
                        <td class="px-4 py-2">{{ $emp->rol->nombre }}</td>

                        {{-- ESTADO REAL --}}
                        <td class="px-4 py-2">
                            @if($emp->estado === 'activo')
                                <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">
                                    Activo
                                </span>
                            @elseif($emp->estado === 'pendiente')
                                <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-700">
                                    Pendiente
                                </span>
                            @elseif($emp->estado === 'bloqueado')
                                <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">
                                    Bloqueado
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500">
                            No hay empleados con esos filtros.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- PAGINACIÓN --}}
            <div class="mt-4">
                {{ $empleados->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
