<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $empresa->exists ? __('Editar Empresa') : __('Nueva Empresa') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <form action="{{ $action }}" method="POST">
                @csrf
                @if($method === 'PUT')
                    @method('PUT')
                @endif

                <div class="mb-4">
                    <label for="nombre" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Nombre</label>
                    <input type="text" name="nombre" id="nombre"
                        value="{{ old('nombre', $empresa->nombre) }}"
                        class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">
                    @error('nombre')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                </div>

                <div class="mb-4">
                    <label for="limite_usuarios" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Límite de usuarios</label>
                    <input type="number" name="limite_usuarios" id="limite_usuarios"
                        value="{{ old('limite_usuarios', $empresa->limite_usuarios) }}"
                        class="w-full px-3 py-2 border rounded dark:bg-gray-700 dark:text-white" min="1">
                    @error('limite_usuarios')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('empresas.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-sartu-rojo text-white rounded hover:bg-sartu-rojo-oscuro">
                        {{ $empresa->exists ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
