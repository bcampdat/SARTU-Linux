<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-sartu-negro">
            Resumen Diario - Empresa
        </h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto">

        <form method="GET" class="mb-4 flex items-center gap-3">
            <label for="fecha" class="font-semibold">Fecha:</label>
            <input type="date" name="fecha" value="{{ $fecha }}" class="border px-2 py-1 rounded">
            <button class="px-4 py-1 bg-sartu-rojo text-white rounded shadow">Filtrar</button>
        </form>

        <div class="overflow-x-auto bg-white shadow rounded">

            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-sartu-marron text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Empleado</th>
                        <th class="px-4 py-2 text-center">Trabajado</th>
                        <th class="px-4 py-2 text-center">Pausas</th>
                        <th class="px-4 py-2 text-center">Total</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($empleados as $emp)
                    @php
                        $r = $resumenes[$emp->id] ?? null;
                    @endphp

                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">
                            {{ $emp->name }}

                            {{-- ALERTAS --}}
                            @if($r && $r->alerta_pausa)
                                <span class="ml-2 px-2 py-1 bg-yellow-500 text-white rounded text-sm">
                                    ⚠ Pausa excedida
                                </span>
                            @endif

                            @if($r && $r->alerta_jornada)
                                <span class="ml-2 px-2 py-1 bg-red-600 text-white rounded text-sm">
                                    ⚠ Jornada excedida
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-2 text-center">{{ $r->tiempo_trabajado ?? 0 }} min</td>
                        <td class="px-4 py-2 text-center">{{ $r->tiempo_pausas ?? 0 }} min</td>
                        <td class="px-4 py-2 text-center font-bold">{{ $r->tiempo_total ?? 0 }} min</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>

</x-app-layout>
