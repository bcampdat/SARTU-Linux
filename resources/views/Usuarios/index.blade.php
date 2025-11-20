<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Usuarios</h2>
    </x-slot>

    <div class="py-6">
        <a href="{{ route('usuarios.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded">Crear Usuario</a>

        <table class="min-w-full mt-4 table-auto border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Rol</th>
                    <th class="px-4 py-2">Empresa</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $usuario->id_usuario }}</td>
                        <td class="px-4 py-2">{{ $usuario->nombre }}</td>
                        <td class="px-4 py-2">{{ $usuario->email }}</td>
                        <td class="px-4 py-2">{{ $usuario->rol->nombre }}</td>
                        <td class="px-4 py-2">{{ $usuario->empresa->nombre ?? 'N/A' }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="text-blue-600">Editar</a>
                            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600" onclick="return confirm('¿Eliminar usuario?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
