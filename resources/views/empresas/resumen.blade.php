x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-sartu-negro">
            Resumen Empresa
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto space-y-6">

        {{-- FILTRO ADMIN --}}
        @if(auth()->user()->rol_id === 1 || auth()->user()->rol_id === 2)
            <form method="GET" class="flex flex-wrap gap-4 items-center mb-6">
                <div>
                    <label for="empresa_id" class="font-semibold text-sm">Empresa:</label>
                    <select name="empresa_id" class="border px-3 py-2 rounded"
                            onchange="this.form.submit()">
                        @foreach($empresas as $emp)
                            <option value="{{ $emp->id_empresa }}"
                                @selected($empresaId == $emp->id_empresa)>
                                {{ $emp->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="fecha" class="font-semibold text-sm">Fecha:</label>
                    <input type="date" name="fecha" value="{{ $fecha }}"
                           class="border px-3 py-2 rounded">
                </div>

                <button class="px-4 py-2 bg-sartu-rojo text-white rounded shadow">
                    Filtrar
                </button>
            </form>
        @endif

        {{-- MÉTRICAS GENERALES --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            <div class="bg-sartu-rojo text-white p-6 rounded-xl shadow text-center">
                <p class="text-sm uppercase opacity-80">Trabajadores</p>
                <p class="text-4xl font-bold mt-2">{{ $resumenes->count() }}</p>
            </div>

            <div class="bg-sartu-marron text-white p-6 rounded-xl shadow text-center">
                <p class="text-sm uppercase opacity-80">Total trabajado</p>
                <p class="text-4xl font-bold mt-2">{{ $totalTrabajado }} min</p>
            </div>

            <div class="bg-sartu-gris-oscuro text-white p-6 rounded-xl shadow text-center">
                <p class="text-sm uppercase opacity-80">Total pausas</p>
                <p class="text-4xl font-bold mt-2">{{ $totalPausas }} min</p>
            </div>

            <div class="bg-black text-white p-6 rounded-xl shadow text-center">
                <p class="text-sm uppercase opacity-80">Fecha</p>
                <p class="text-xl font-bold mt-2">{{ $fecha }}</p>
            </div>

        </div>

        {{-- ALERTAS --}}
        @if(count($alertas))
            <div class="bg-red-600 text-white p-4 rounded-xl shadow">
                <h3 class="font-bold mb-2">⚠ Alertas de Jornada</h3>
                <ul class="list-disc ml-6 space-y-1">
                    @foreach($alertas as $a)
                        <li>{{ $a }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- TABLA RESUMEN --}}
        <div class="bg-white shadow-xl rounded-xl p-6">

            <h3 class="text-xl font-semibold mb-4 text-sartu-negro">
                Resumen por empleado
            </h3>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left border border-sartu-gris-oscuro rounded-lg">

                    <thead class="bg-sartu-marron text-white">
                        <tr>
                            <th class="px-4 py-3">Empleado</th>
                            <th class="px-4 py-3 text-center">Trabajado</th>
                            <th class="px-4 py-3 text-center">Pausas</th>
                            <th class="px-4 py-3 text-center">Total</th>
                            <th class="px-4 py-3 text-center">Estado</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($resumenes as $r)
                            <tr class="border-b hover:bg-gray-100">

                                <td class="px-4 py-3 font-medium text-sartu-negro">
                                    {{ $r->usuario->name }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $r->tiempo_trabajado }} min
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $r->tiempo_pausas }} min
                                </td>

                                <td class="px-4 py-3 text-center font-bold">
                                    {{ $r->tiempo_total }} min
                                </td>

                                <td class="px-4 py-3 text-center">
                                    @if($r->tiempo_trabajado > auth()->user()->empresa->jornada_diaria_minutos)
                                        <span class="px-3 py-1 rounded-full bg-red-600 text-white text-sm">
                                            Exceso
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-green-600 text-white text-sm">
                                            Correcto
                                        </span>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-gray-500">
                                    No hay datos para esta fecha.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>

    </div>
</x-app-layout>
