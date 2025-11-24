<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-sartu-negro">
            Crear Usuario
        </h2>
    </x-slot>

    <div class="py-6">
        <form action="{{ route('usuarios.store') }}" method="POST" class="bg-white p-6 rounded shadow max-w-xl mx-auto">
            @csrf

            @include('usuarios._form')

            <button type="submit"
                class="mt-4 px-4 py-2 bg-sartu-rojo text-white rounded hover:bg-sartu-rojo-oscuro">
                Guardar Usuario
            </button>
        </form>
    </div>
</x-app-layout>
