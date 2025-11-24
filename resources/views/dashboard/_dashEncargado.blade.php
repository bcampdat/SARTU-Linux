<div class="max-w-7xl mx-auto space-y-8">

    {{-- BOTÓN DE MI FICHAJE (encargado también ficha) --}}
    <div class="mb-6">
        <a href="{{ route('fichajes.create') }}"
            class="inline-block bg-sartu-rojo text-white px-6 py-3 rounded-xl text-lg font-semibold shadow hover:bg-sartu-rojo-oscuro">
            Mi Fichaje
        </a>
    </div>
 

    {{-- Métricas de empleados --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-sartu-rojo p-6 rounded-xl text-white shadow-lg text-center">
            <h3 class="text-sm uppercase">Total Empleados</h3>
            <p class="text-4xl font-bold mt-2">{{ $empleadosTotal }}</p>
        </div>

        <div class="bg-sartu-marron p-6 rounded-xl text-white shadow-lg text-center">
            <h3 class="text-sm uppercase">Activos</h3>
            <p class="text-4xl font-bold mt-2">{{ $totalUsuariosActivos }}</p>
        </div>

        <div class="bg-sartu-gris-oscuro p-6 rounded-xl text-white shadow-lg text-center">
            <h3 class="text-sm uppercase">Entradas Hoy</h3>
            <p class="text-4xl font-bold mt-2">{{ $totalEntradasHoy }}</p>
        </div>

        <div class="bg-sartu-negro p-6 rounded-xl text-white shadow-lg text-center">
            <h3 class="text-sm uppercase">Salidas Hoy</h3>
            <p class="text-4xl font-bold mt-2">{{ $totalSalidasHoy }}</p>
        </div>

    </div>

    {{-- Resumen tiempos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-sartu-rojo-oscuro p-6 rounded-xl text-white shadow-lg text-center">
            <h3 class="text-sm uppercase opacity-80">Tiempo Trabajado Total (min)</h3>
            <p class="text-4xl font-bold mt-2">{{ $tiempoTrabajadoTotal }}</p>
        </div>

        <div class="bg-sartu-marron p-6 rounded-xl text-white shadow-lg text-center">
            <h3 class="text-sm uppercase opacity-80">Tiempo Pausas (min)</h3>
            <p class="text-4xl font-bold mt-2">{{ $tiempoPausasTotal }}</p>
        </div>
    </div>

    {{-- Últimos fichajes de sus empleados --}}
    <div class="bg-white p-6 rounded-xl shadow-xl">

        <h3 class="text-xl font-semibold mb-4 text-sartu-negro">
            Últimos fichajes de la empresa
        </h3>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-sartu-gris-oscuro rounded">

                <thead class="bg-sartu-marron text-white">
                    <tr>
                        <th class="px-4 py-3">Empleado</th>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Fecha</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-sartu-gris-oscuro">
                    @foreach($ultimosFichajes as $f)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-3">{{ $f->usuario->name }}</td>
                        <td class="px-4 py-3">{{ ucfirst($f->tipo) }}</td>
                        <td class="px-4 py-3">{{ $f->fecha_hora->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>

</div>

