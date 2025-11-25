<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-sartu-negro dark:text-white">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             
            {{-- Convertir las claves de $data en variables --}}
            @php extract($data); @endphp
            
            {{-- Incluir la vista correspondiente con las variables ya cargadas --}}
            @include('dashboard.' . $vista)

        </div>
    </div>
</x-app-layout>
