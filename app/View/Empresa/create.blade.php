<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Crear Empresa</h2>
    </x-slot>

    <div class="py-6">
        <form action="{{ route('empresas.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label>Nombre</label>
                <input type="text" name="nombre" class="border rounded px-2 py-1 w-full" required>
            </div>
            <div>
                <label>Límite de Usuarios</label>
                <input type="number" name="limite_usuarios" class="border rounded px-2 py-1 w-full" min="1" required>
            </div>
            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">Crear</button>
        </form>
    </div>
</x-app-layout>