<div class="max-w-7xl mx-auto space-y-8">

    {{-- Métricas globales --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-sartu-rojo p-6 rounded-xl text-white shadow-lg text-center">
            <h3 class="text-sm uppercase opacity-80">Usuarios Activos</h3>
            <p class="text-4xl font-bold mt-2">{{ $totalUsuariosActivos }}</p>
        </div>

        <div class="bg-sartu-marron p-6 rounded-xl text-white shadow-lg text-center">
            <h3 class="text-sm uppercase opacity-80">Usuarios Inactivos</h3>
            <p class="text-4xl font-bold mt-2">{{ $totalUsuariosInactivos }}</p>
        </div>

        <div class="bg-sartu-gris-oscuro p-6 rounded-xl text-white shadow-lg text-center">
            <h3 class="text-sm uppercase opacity-80">Entradas Hoy</h3>
            <p class="text-4xl font-bold mt-2">{{ $totalEntradasHoy }}</p>
        </div>

        <div class="bg-sartu-negro p-6 rounded-xl text-white shadow-lg text-center">
            <h3 class="text-sm uppercase opacity-80">Salidas Hoy</h3>
            <p class="text-4xl font-bold mt-2">{{ $totalSalidasHoy }}</p>
        </div>

    </div>

    {{-- Resumen de tiempos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-sartu-rojo-oscuro p-6 rounded-xl text-white shadow-lg text-center">
            <h3 class="text-sm uppercase opacity-80">Tiempo Trabajado Hoy (min)</h3>
            <p class="text-4xl font-bold mt-2">{{ $tiempoTrabajadoTotal }}</p>
        </div>

        <div class="bg-sartu-marron p-6 rounded-xl text-white shadow-lg text-center">
            <h3 class="text-sm uppercase opacity-80">Tiempo Pausas Hoy (min)</h3>
            <p class="text-4xl font-bold mt-2">{{ $tiempoPausasTotal }}</p>
        </div>
    </div>

    {{-- Tabla de últimos fichajes --}}
    <div class="bg-white shadow-xl rounded-xl p-6">

        <h3 class="text-xl font-semibold mb-4 text-sartu-negro">Últimos Fichajes</h3>

        <div class="overflow-x-auto">
            <table class="min-w-full text-left border border-sartu-gris-oscuro rounded-lg">

                <thead class="bg-sartu-marron text-white">
                    <tr>
                        <th class="px-4 py-3">Usuario</th>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Fecha / Hora</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-sartu-gris-oscuro">
                    @foreach($ultimosFichajes as $f)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-3">{{ $f->usuario->name ?? '—' }}</td>
                            <td class="px-4 py-3">{{ ucfirst($f->tipo) }}</td>
                            <td class="px-4 py-3">{{ $f->fecha_hora->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>

</div>
