<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">Crear Empresa</h2>
    </x-slot>

    <div class="py-6">
        <form method="POST" action="{{ route('empresas.store') }}"
              class="bg-white p-6 rounded shadow max-w-xl mx-auto">
            @csrf

            @include('empresas._form')

            <button class="mt-4 px-4 py-2 bg-sartu-rojo text-white rounded hover:bg-sartu-rojo-oscuro">
                Guardar
            </button>
        </form>
    </div>
</x-app-layout>
