<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-sartu-white">
            Crear Usuario
        </h2>
    </x-slot>

    <div class="py-6">
        <form action="{{ route('usuarios.store') }}" method="POST" class="bg-white p-6 rounded shadow max-w-xl mx-auto">
            @csrf

            @include('usuarios._form')

            <div class="mt-6 flex justify-between">
                <a href="{{ route('usuarios.index') }}"
                    class="px-4 py-2  text-gris-oscuro rounded hover:text-sartu-rojo-oscuro">
                    Cancelar
                </a>

                <button class="px-4 py-2 bg-sartu-rojo text-white rounded hover:bg-sartu-rojo-oscuro">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
