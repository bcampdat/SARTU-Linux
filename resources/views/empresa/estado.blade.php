<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Estado de la Empresa
        </h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto space-y-6">

        {{-- FILTRO SOLO PARA ADMIN --}}
        @if(auth()->user()->rol_id === 1)
        <form method="GET" class="bg-white p-4 rounded shadow mb-6 flex gap-4 items-center">
            <label class="font-semibold">Empresa:</label>

            <select name="empresa_id"
                class="border px-3 py-2 rounded"
                onchange="this.form.submit()">
                @foreach($empresas as $emp)
                <option value="{{ $emp->id_empresa }}"
                    @selected($empresaId==$emp->id_empresa)>
                    {{ $emp->nombre }}
                </option>
                @endforeach
            </select>
        </form>
        @endif

        {{-- FILTROS --}}
        <form method="GET" class="bg-white p-4 rounded shadow flex items-center gap-4 flex-nowrap overflow-x-auto">

            {{-- Mantener empresa si es admin --}}
            @if(auth()->user()->rol_id === 1)
            <input type="hidden" name="empresa_id" value="{{ $empresaId }}">
            @endif

            <input
                type="text"
                name="empleado"
                value="{{ request('empleado') }}"
                placeholder="Empleado"
                class="border rounded px-3 py-2 w-48">

            <select name="estado" class="border rounded px-3 py-2 w-44">
                <option value="">Estado</option>
                <option value="entrada" @selected(request('estado')=='entrada' )>Trabajando</option>
                <option value="pausa" @selected(request('estado')=='pausa' )>Pausa</option>
                <option value="salida" @selected(request('estado')=='salida' )>Salida</option>
            </select>

            <input
                type="number"
                name="anio"
                value="{{ request('anio', now()->year) }}"
                placeholder="Año"
                class="border rounded px-3 py-2 w-28">

            <input
                type="date"
                name="desde"
                value="{{ request('desde') }}"
                class="border rounded px-3 py-2 w-40">

            <input
                type="date"
                name="hasta"
                value="{{ request('hasta') }}"
                class="border rounded px-3 py-2 w-40">

            <button class="bg-sartu-rojo text-white px-4 py-2 rounded whitespace-nowrap">
                Filtrar
            </button>

            <a href="{{ route('empresa.estado', ['empresa_id' => $empresaId]) }}"
                class="bg-gray-300 px-4 py-2 rounded whitespace-nowrap">
                Limpiar
            </a>

        </form>


        {{-- TABLA --}}
        <div class="bg-white shadow rounded p-6">

            <h3 class="text-xl font-semibold mb-4">
                Estado actual de los trabajadores
            </h3>

            <div class="overflow-x-auto">
                <table class="min-w-full border text-sm">

                    <thead class="bg-sartu-rojo text-white">
                        <tr>
                            <th class="px-4 py-2 text-left">Empleado</th>
                            <th class="px-4 py-2 text-center">Estado</th>
                            <th class="px-4 py-2 text-center">Desde</th>
                            <th class="px-4 py-2 text-center">Hoy</th>
                            <th class="px-4 py-2 text-center">Mes</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse($empleados as $emp)
                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-2 font-medium">
                                {{ $emp->name }}
                            </td>

                            <td class="px-4 py-2 text-center">
                                @if($emp->estado_actual === 'entrada' || $emp->estado_actual === 'reanudar')
                                <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">
                                    Trabajando
                                </span>
                                @elseif($emp->estado_actual === 'pausa')
                                <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-700">
                                    En pausa
                                </span>
                                @else
                                <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-700">
                                    Salida
                                </span>
                                @endif
                            </td>

                            <td class="px-4 py-2 text-center text-gray-700">
                                {{ $emp->hora_estado?->format('H:i') ?? '—' }}
                            </td>

                            <td class="px-4 py-2 text-center text-gray-700">
                                {{ $emp->hoy ?? '—' }}
                            </td>

                            <td class="px-4 py-2 text-center text-gray-700">
                                {{ $emp->mes ?? '—' }}
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500">
                                No hay empleados para esta empresa.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>

    </div>
</x-app-layout>
