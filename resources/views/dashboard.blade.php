<x-app-layout>
    <x-slot name="header">
        <h2 class="font-sans font-semibold text-2xl text-sartu-negro dark:text-white leading-tight">
            Dashboard SARTU
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Usuarios activos/inactivos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-sartu-rojo shadow-lg rounded-lg p-6 text-center text-white">
                    <h3 class="text-sm font-medium uppercase">Usuarios Activos</h3>
                    <p class="text-3xl font-bold mt-2">{{ $totalUsuariosActivos }}</p>
                </div>
                <div class="bg-sartu-marron shadow-lg rounded-lg p-6 text-center text-white">
                    <h3 class="text-sm font-medium uppercase">Usuarios Inactivos</h3>
                    <p class="text-3xl font-bold mt-2">{{ $totalUsuariosInactivos }}</p>
                </div>
            </div>

            <!-- Fichajes hoy -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-sartu-gris-oscuro shadow-lg rounded-lg p-6 text-center text-white">
                    <h3 class="text-sm font-medium uppercase">Entradas hoy</h3>
                    <p class="text-3xl font-bold mt-2">{{ $totalEntradasHoy }}</p>
                </div>
                <div class="bg-sartu-negro shadow-lg rounded-lg p-6 text-center text-white">
                    <h3 class="text-sm font-medium uppercase">Salidas hoy</h3>
                    <p class="text-3xl font-bold mt-2">{{ $totalSalidasHoy }}</p>
                </div>
            </div>

            <!-- Resumen diario -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-sartu-rojo-oscuro shadow-lg rounded-lg p-6 text-center text-white">
                    <h3 class="text-sm font-medium uppercase">Tiempo trabajado hoy (min)</h3>
                    <p class="text-3xl font-bold mt-2">{{ $tiempoTrabajadoTotal }}</p>
                </div>
                <div class="bg-sartu-marron shadow-lg rounded-lg p-6 text-center text-white">
                    <h3 class="text-sm font-medium uppercase">Tiempo de pausas hoy (min)</h3>
                    <p class="text-3xl font-bold mt-2">{{ $tiempoPausasTotal }}</p>
                </div>
            </div>

            <!-- Últimos fichajes -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                <h3 class="text-lg font-semibold text-sartu-negro dark:text-white mb-4">Últimos fichajes</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-sartu-gris-oscuro">
                        <thead class="bg-sartu-marron text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Usuario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Fecha / Hora</th>
                            </tr>
                        </thead>
                        <tbody class="bg-sartu-gris-oscuro divide-y divide-sartu-negro text-white">
                            @foreach($ultimosFichajes as $fichaje)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        {{ $fichaje->usuario->name ?? 'Desconocido' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ ucfirst($fichaje->tipo) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $fichaje->timestamp->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
