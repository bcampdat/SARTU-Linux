<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-sartu-negro">
            Mi Resumen de Hoy
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto space-y-10">

            {{-- Resumen Diario --}}
            <div class="bg-white p-8 rounded-2xl shadow-xl">
                <h2 class="text-2xl font-semibold text-sartu-negro mb-6">
                    Mi Jornada
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">

                    <div class="bg-sartu-rojo text-white p-6 rounded-xl shadow-lg">
                        <p class="text-sm uppercase opacity-80">Trabajado Hoy</p>
                        <p class="text-3xl font-bold mt-2">
                            {{ $MiResumen->tiempo_trabajado ?? 0 }} min
                        </p>
                    </div>

                    <div class="bg-sartu-marron text-white p-6 rounded-xl shadow-lg">
                        <p class="text-sm uppercase opacity-80">Pausas Hoy</p>
                        <p class="text-3xl font-bold mt-2">
                            {{ $MiResumen->tiempo_pausas ?? 0 }} min
                        </p>
                    </div>

                    <div class="bg-sartu-gris-oscuro text-white p-6 rounded-xl shadow-lg">
                        <p class="text-sm uppercase opacity-80">Total</p>
                        <p class="text-3xl font-bold mt-2">
                            {{ $MiResumen->tiempo_total ?? 0 }} min
                        </p>
                    </div>

                </div>
            </div>

            {{-- Fichajes del Día --}}
            <div class="bg-white p-8 rounded-2xl shadow-xl">
                <h3 class="text-xl font-semibold text-sartu-negro mb-4">
                    Mis fichajes de hoy
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-sartu-gris-oscuro rounded-xl">
                        <thead class="bg-sartu-marron text-white">
                            <tr>
                                <th class="px-4 py-3">Tipo</th>
                                <th class="px-4 py-3">Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fichajesHoy as $f)
                                <tr class="border-b hover:bg-gray-100">
                                    <td class="px-4 py-3">{{ ucfirst($f->tipo) }}</td>
                                    <td class="px-4 py-3">{{ $f->fecha_hora->format('H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-center text-gray-500">
                                        Aún no has fichado hoy.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Últimos Fichajes --}}
            <div class="bg-white p-8 rounded-2xl shadow-xl">
                <h3 class="text-xl font-semibold mb-4 text-sartu-negro">
                    Últimos fichajes
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border border-sartu-gris-oscuro rounded-lg">

                        <thead class="bg-sartu-marron text-white">
                            <tr>
                                <th class="px-4 py-3">Tipo</th>
                                <th class="px-4 py-3">Fecha / Hora</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($misUltimosFichajes as $f)
                                <tr class="border-b hover:bg-gray-100">
                                    <td class="px-4 py-3">{{ ucfirst($f->tipo) }}</td>
                                    <td class="px-4 py-3">{{ $f->fecha_hora->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
