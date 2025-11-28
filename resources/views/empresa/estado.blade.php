<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-sartu-white">
            Estado de la Empresa
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto space-y-6">

        {{-- FILTRO SOLO PARA ADMIN --}}
        @if(auth()->user()->rol_id === 1)
            <form method="GET" class="flex gap-4 items-center mb-6">
                <label for ="empresa_id" class="font-semibold">Empresa:</label>
                <select name="empresa_id" class="border px-3 py-2 rounded"
                        onchange="this.form.submit()">
                    @foreach($empresas as $emp)
                        <option value="{{ $emp->id_empresa }}"
                            @selected($empresaId == $emp->id_empresa)>
                            {{ $emp->nombre }}
                        </option>
                    @endforeach
                </select>
            </form>
        @endif

        <div class="bg-white shadow-xl rounded-xl p-6">

            <h3 class="text-xl font-semibold mb-4 text-sartu-negro">
                Estado actual de los trabajadores
            </h3>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left border border-sartu-gris-oscuro rounded-lg">

                    <thead class="bg-sartu-marron text-white">
                        <tr>
                            <th class="px-4 py-3">Empleado</th>
                            <th class="px-4 py-3 text-center">Estado</th>
                            <th class="px-4 py-3 text-center">Desde</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($empleados as $emp)
                            <tr class="border-b hover:bg-gray-100">

                                <td class="px-4 py-3 font-medium text-sartu-negro">
                                    {{ $emp->name }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    @if($emp->estado_actual === 'entrada' || $emp->estado_actual === 'reanudar')
                                        <span class="px-3 py-1 rounded-full bg-green-600 text-white text-sm">
                                            Trabajando
                                        </span>
                                    @elseif($emp->estado_actual === 'pausa')
                                        <span class="px-3 py-1 rounded-full bg-yellow-500 text-white text-sm">
                                            En pausa
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-red-600 text-white text-sm">
                                            Fuera
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-center text-gray-700">
                                    {{ $emp->hora_estado?->format('H:i') ?? '—' }}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-6 text-gray-500">
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
