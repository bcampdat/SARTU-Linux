<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">
            Gestión de Empresas
        </h2>
    </x-slot>

    <div class="py-6">

        @if(session('success'))
        <div class="bg-green-500 text-white px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-600 text-white px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
        @endif

        <a href="{{ route('empresas.create') }}"
            class="px-4 py-2 bg-sartu-rojo text-white rounded shadow hover:bg-sartu-rojo-oscuro">
            ➕ Crear Empresa
        </a>
        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full rounded border border-sartu-gris-oscuro bg-white">
                <thead class="bg-sartu-marron text-white">
                    <tr>
                        <th class="px-4 py-2">Nombre</th>
                        <th class="px-4 py-2 text-center">Límite Usuarios</th>
                        <th class="px-4 py-2 text-center">Jornada (min)</th>
                        <th class="px-4 py-2 text-center">Pausa Máx (min)</th>
                        <th class="px-4 py-2 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-sartu-gris-oscuro">
                    @foreach ($empresas as $empresa)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2">{{ $empresa->nombre }}</td>
                        <td class="px-4 py-2 text-center">{{ $empresa->limite_usuarios }}</td>
                        <td class="px-4 py-2 text-center">{{ $empresa->jornada_diaria_minutos }}</td>
                        <td class="px-4 py-2 text-center">{{ $empresa->max_pausa_no_contabilizada }}</td>

                        <td class="px-4 py-2 text-center">
                            <a href="{{ route('empresas.edit', $empresa->id_empresa) }}"
                                class="text-blue-600 mr-3 hover:underline">Editar</a>

                            <form action="{{ route('empresas.destroy', $empresa->id_empresa) }}"
                                method="POST"
                                class="inline"
                                onsubmit="return confirm('¿Eliminar empresa?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
