<div class="max-w-7xl mx-auto space-y-10">

    {{-- ============================= --}}
    {{-- 1) MI FICHAJE + MIS METRICAS --}}
    {{-- ============================= --}}

    {{-- Barra de progreso --}}
    <div class="w-full bg-gray-300 rounded-full h-5 overflow-hidden mb-4">
        <div class="h-5 bg-sartu-rojo-oscuro transition-all duration-500"
            style="--p: {{ $progresoJornada ?? 0 }}%; width: var(--p);">
        </div>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-xl">

        <h2 class="text-2xl font-semibold text-sartu-negro mb-6">
            Mi Jornada de Hoy
        </h2>

        {{-- Botón de Fichaje --}}
        <div class="mb-6">
            <a href="{{ route('fichajes.create') }}"
                class="inline-block bg-sartu-rojo text-white px-6 py-3 rounded-xl text-lg font-semibold shadow hover:bg-sartu-rojo-oscuro">
                Fichar
            </a>
        </div>

        {{-- Resumen personal --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">

            <div class="bg-sartu-rojo text-white p-6 rounded-xl shadow-lg">
                <p class="text-sm uppercase opacity-80">Trabajado Hoy</p>
                <p class="text-3xl font-bold mt-2">
                    {{ $miResumen->tiempo_trabajado ?? 0 }} min
                </p>
            </div>

            <div class="bg-sartu-marron text-white p-6 rounded-xl shadow-lg">
                <p class="text-sm uppercase opacity-80">Pausas Hoy</p>
                <p class="text-3xl font-bold mt-2">
                    {{ $miResumen->tiempo_pausas ?? 0 }} min
                </p>
            </div>

            <div class="bg-sartu-gris-oscuro text-white p-6 rounded-xl shadow-lg">
                <p class="text-sm uppercase opacity-80">Total</p>
                <p class="text-3xl font-bold mt-2">
                    {{ $miResumen->tiempo_total ?? 0 }} min
                </p>
            </div>

        </div>


        {{-- Últimos fichajes del encargado --}}
        <div class="mt-10">
            <h3 class="text-xl font-semibold text-sartu-negro mb-4">Mis últimos fichajes</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-sartu-gris-oscuro rounded-xl">
                    <thead class="bg-sartu-marron text-white">
                        <tr>
                            <th class="px-4 py-3">Tipo</th>
                            <th class="px-4 py-3">Fecha / Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($misUltimosFichajes as $f)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="px-4 py-3">{{ ucfirst($f->tipo) }}</td>
                            <td class="px-4 py-3">{{ $f->fecha_hora->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td class="px-4 py-3 text-center text-gray-500" colspan="2">
                                Aún no has fichado hoy.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>


    {{-- ==================================== --}}
    {{-- 2) MÉTRICAS DE LA EMPRESA (GLOBAL) --}}
    {{-- ==================================== --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-sartu-rojo text-white p-6 rounded-xl shadow-lg text-center">
            <h3 class="text-sm uppercase">Total Empleados</h3>
            <p class="text-4xl font-bold mt-2">{{ $empleadosTotal }}</p>
        </div>

        <div class="bg-sartu-marron text-white p-6 rounded-xl shadow-lg text-center">
            <h3 class="text-sm uppercase">Activos</h3>
            <p class="text-4xl font-bold mt-2">{{ $totalUsuariosActivos }}</p>
        </div>

        <div class="bg-sartu-gris-oscuro text-white p-6 rounded-xl shadow-lg text-center">
            <h3 class="text-sm uppercase">Entradas Hoy</h3>
            <p class="text-4xl font-bold mt-2">{{ $totalEntradasHoy }}</p>
        </div>

        <div class="bg-sartu-negro text-white p-6 rounded-xl shadow-lg text-center">
            <h3 class="text-sm uppercase">Salidas Hoy</h3>
            <p class="text-4xl font-bold mt-2">{{ $totalSalidasHoy }}</p>
        </div>

    </div>


    {{-- ====================================== --}}
    {{-- 3) ÚLTIMOS FICHAJES DE LA EMPRESA     --}}
    {{-- ====================================== --}}
    <div class="bg-white p-6 rounded-xl shadow-xl">

        <h3 class="text-xl font-semibold mb-4 text-sartu-negro">
            Últimos fichajes de la empresa
        </h3>

        <div class="overflow-x-auto">
            <table class="min-w-full text-left border border-sartu-gris-oscuro rounded-lg">

                <thead class="bg-sartu-marron text-white">
                    <tr>
                        <th class="px-4 py-3">Empleado</th>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Fecha / Hora</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($ultimosFichajesEmpresa as $f)
                    <tr class="border-b hover:bg-gray-100">
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
