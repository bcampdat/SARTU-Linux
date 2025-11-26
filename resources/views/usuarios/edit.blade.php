<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-sartu-negro">
            Editar Usuario
        </h2>
    </x-slot>

    <div class="py-6">
        <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST" class="bg-white p-6 rounded shadow max-w-xl mx-auto">
            @csrf
            @method('PUT')

            @include('usuarios._form', ['usuario' => $usuario])

            <button type="submit"
                class="mt-4 px-4 py-2 bg-sartu-rojo text-white rounded hover:bg-sartu-rojo-oscuro">
                Actualizar Usuario
            </button>

            <a href="{{ url()->previous() }}"
                class="mt-4 px-4 py-2 bg-sartu-marron text-white rounded hover:bg-sartu-gris-oscuro">
                Cancelar
            </a>
        </form>
    </div>
</x-app-layout>