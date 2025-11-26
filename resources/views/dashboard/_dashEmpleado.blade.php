<div class="max-w-3xl mx-auto space-y-10">

    {{-- MI FICHAJE UNIFICADO --}}
    @include('fichajes._miFichaje', [
    'resumen' => $resumen,
    'ultimoTipo' => $estado,
    'empresa' => auth()->user()->empresa,
    'ultimoFichaje' => $ultimoFichaje
    ])


    {{-- MIS FICHAJES DE HOY --}}
    <div class="bg-white p-8 rounded-xl shadow-xl">

        <h3 class="text-xl font-semibold mb-4">
            Mis fichajes de hoy
        </h3>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-sartu-marron text-white">
                    <tr>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fichajesHoy as $f)
                    <tr class="border-b">
                        <td class="px-4 py-3">{{ ucfirst($f->tipo) }}</td>
                        <td class="px-4 py-3">{{ $f->fecha_hora->format('H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center text-gray-500 py-4">
                            Sin fichajes hoy
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

