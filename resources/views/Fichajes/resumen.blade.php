<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-white">Resumen Diario</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto space-y-6">

        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-bold mb-4">Mi jornada de hoy</h3>

            <p><strong>Tiempo trabajado:</strong> {{ $resumen->tiempo_trabajado }} min</p>
            <p><strong>Pausas:</strong> {{ $resumen->tiempo_pausas }} min</p>
            <p><strong>Total:</strong> {{ $resumen->tiempo_total }} min</p>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-bold mb-4">Fichajes de hoy</h3>

            <ul class="space-y-2">
                @foreach($fichajes as $f)
                    <li>
                        <strong>{{ ucfirst($f->tipo) }}</strong>  
                        — {{ $f->fecha_hora->format('H:i') }}
                    </li>
                @endforeach
            </ul>
        </div>

    </div>
</x-app-layout>
