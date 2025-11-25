<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-white">Mi Resumen Diario</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto space-y-6">

        <form method="GET" class="mb-4">
            <label for ="fecha" class="font-semibold">Fecha:</label>
            <input type="date" name="fecha" value="{{ $fecha }}" class="border px-2 py-1">
            <button class="px-4 py-1 bg-sartu-rojo text-white rounded ml-2">Filtrar</button>
        </form>

        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-bold mb-4">Mi jornada ({{ $fecha }})</h3>

            @if($resumen)
                <p><strong>Tiempo trabajado:</strong> {{ $resumen->tiempo_trabajado }} min</p>
                <p><strong>Pausas:</strong> {{ $resumen->tiempo_pausas }} min</p>
                <p><strong>Total:</strong> {{ $resumen->tiempo_total }} min</p>
            @else
                <p class="text-gray-500">Sin datos para esta fecha.</p>
            @endif
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-bold mb-4">Mis fichajes</h3>

            @if(count($fichajes))
                <ul class="space-y-2">
                    @foreach($fichajes as $f)
                        <li>
                            <strong>{{ ucfirst($f->tipo) }}</strong>
                            — {{ $f->fecha_hora->format('H:i') }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">No hay fichajes este día.</p>
            @endif
        </div>

    </div>
</x-app-layout>
