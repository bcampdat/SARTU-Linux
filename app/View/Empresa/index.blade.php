<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Empresas</h2>
    </x-slot>

    <div class="py-6">
        <a href="{{ route('empresas.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded">Crear Empresa</a>

        <table class="min-w-full mt-4 table-auto border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Límite de Usuarios</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($empresas as $empresa)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $empresa->id_empresa }}</td>
                        <td class="px-4 py-2">{{ $empresa->nombre }}</td>
                        <td class="px-4 py-2">{{ $empresa->limite_usuarios }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('empresas.edit', $empresa) }}" class="text-blue-600">Editar</a>
                            <form action="{{ route('empresas.destroy', $empresa) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600" onclick="return confirm('¿Eliminar empresa?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>