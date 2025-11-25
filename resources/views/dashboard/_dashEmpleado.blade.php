<div class="max-w-3xl mx-auto space-y-10">

    {{-- PROGRESO DE LA JORNADA --}}
    <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-xl text-center">

        <h3 class="text-2xl font-semibold text-sartu-negro dark:text-white mb-6">
            Mi Jornada de Hoy
        </h3>
        
        {{-- Barra de progreso --}}
        <div class="w-full bg-gray-300 rounded-full h-5 overflow-hidden mb-4">
            <div class="h-5 bg-sartu-rojo-oscuro transition-all duration-500"
                style="--p: {{ $progresoJornada ?? 0 }}%; width: var(--p);">
            </div>
        </div>

        <p class="text-lg text-sartu-negro dark:text-gray-300">
            Progreso: <strong>{{ $progresoJornada ?? 0 }}%</strong>
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">

            <div class="bg-sartu-rojo text-white p-4 rounded-lg shadow text-center">
                <p class="text-sm uppercase opacity-80">Trabajado</p>
                <p class="text-3xl font-bold mt-1">{{ $tiempoTrabajadoHoy ?? 0 }} min</p>
            </div>

            <div class="bg-sartu-marron text-white p-4 rounded-lg shadow text-center">
                <p class="text-sm uppercase opacity-80">Pausas</p>
                <p class="text-3xl font-bold mt-1">{{ $tiempoPausasHoy ?? 0 }} min</p>
            </div>

            <div class="bg-sartu-gris-oscuro text-white p-4 rounded-lg shadow text-center">
                <p class="text-sm uppercase opacity-80">Objetivo Diario</p>
                <p class="text-3xl font-bold mt-1">{{ $jornadaDiaria ?? 0 }} min</p>
            </div>

        </div>
    </div>

    {{-- BOTÓN DE FICHAJE DIRECTO --}}
    <div class="text-center bg-white dark:bg-gray-800 p-8 rounded-xl shadow-xl">

        <h3 class="text-xl font-semibold text-sartu-negro dark:text-white mb-6">Fichar</h3>
        <form method="POST" action="{{ route('fichajes.store') }}">
            @csrf

            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">

            <button type="submit"
                class="px-10 py-4 bg-sartu-rojo text-white rounded-xl text-2xl shadow hover:bg-sartu-rojo-oscuro">
                {{ $estado === 'trabajando' ? 'Fichar Salida' : 'Fichar Entrada' }}
            </button>
        </form>

        <script>
            navigator.geolocation.getCurrentPosition(
                pos => {
                    document.getElementById('lat').value = pos.coords.latitude;
                    document.getElementById('lng').value = pos.coords.longitude;
                }
            );
        </script>

        <p class="mt-4 text-sartu-negro dark:text-gray-300 text-lg">
            Estado actual:
            @if($estado === 'trabajando')
            <span class="text-green-500 font-bold">Dentro</span>
            @else
            <span class="text-red-500 font-bold">Fuera</span>
            @endif
        </p>

    </div>

    {{-- FICHAJES DEL DÍA --}}
    <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-xl">

        <h3 class="text-xl font-semibold text-sartu-negro dark:text-white mb-4">
            Mis fichajes de hoy
        </h3>

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700 text-left">
                <thead class="bg-sartu-marron text-white">
                    <tr>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Fecha / Hora</th>
                    </tr>
                </thead>

                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($fichajesHoy as $f )
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
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
