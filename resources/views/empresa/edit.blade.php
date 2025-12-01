<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">Editar Empresa</h2>
    </x-slot>

    <div class="py-6">
        <form method="POST"
            action="{{ route('empresa.update', $empresa) }}"
            class="bg-white p-6 rounded shadow max-w-xl mx-auto"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            @include('empresa._form', ['empresa' => $empresa])

            <div class="mt-6 flex justify-between">
                <a href="{{ route('empresa.index') }}"
                    class="px-4 py-2  text-gris-oscuro rounded hover:text-sartu-rojo-oscuro">
                    Cancelar
                </a>

                <button class="px-4 py-2 bg-sartu-rojo text-white rounded hover:bg-sartu-rojo-oscuro">
                    Actualizar
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
