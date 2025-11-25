<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Empleados de mi Empresa</h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto">

        <div class="bg-white shadow rounded p-6">

            <table class="min-w-full border">
                <thead class="bg-sartu-marron text-white">
                    <tr>
                        <th class="px-4 py-2">Nombre</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Rol</th>
                        <th class="px-4 py-2">Activo</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @foreach($empleados as $emp)
                    <tr>
                        <td class="px-4 py-2">{{ $emp->name }}</td>
                        <td class="px-4 py-2">{{ $emp->email }}</td>
                        <td class="px-4 py-2">{{ $emp->rol->nombre }}</td>
                        <td class="px-4 py-2">
                            {{ $emp->activo ? 'Sí' : 'No' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
