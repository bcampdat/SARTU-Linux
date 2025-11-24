<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Fichajes</h2>
    </x-slot>

    <div class="py-6">

        <!-- Formulario para registrar fichaje -->
        <div class="mb-6 p-4 border rounded bg-gray-50">
            <form action="{{ route('fichajes.registrar') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label>Método de fichaje</label>
                    <select name="id_metodo" class="border rounded px-2 py-1 w-full" required>
                        @foreach(\App\Models\MetodoFichaje::all() as $metodo)
                            <option value="{{ $metodo->id_metodo }}">{{ $metodo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Tipo</label>
                    <select name="tipo" class="border rounded px-2 py-1 w-full" required>
                        <option value="entrada">Entrada</option>
                        <option value="salida">Salida</option>
                        <option value="pausa_inicio">Pausa Inicio</option>
                        <option value="pausa_fin">Pausa Fin</option>
                    </select>
                </div>
                <div>
                    <label>Notas (opcional)</label>
                    <input type="text" name="notas" class="border rounded px-2 py-1 w-full">
                </div>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">Registrar Fichaje</button>
            </form>
        </div>

        <!-- Tabla de fichajes -->
        <table class="min-w-full table-auto border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">Usuario</th>
                    <th class="px-4 py-2">Método</th>
                    <th class="px-4 py-2">Tipo</th>
                    <th class="px-4 py-2">Fecha/Hora</th>
                    <th class="px-4 py-2">Notas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fichajes as $fichaje)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $fichaje->usuario->nombre }}</td>
                        <td class="px-4 py-2">{{ $fichaje->metodoFichaje->nombre }}</td>
                        <td class="px-4 py-2">{{ $fichaje->tipo }}</td>
                        <td class="px-4 py-2">{{ $fichaje->timestamp ?? $fichaje->fecha_creacion }}</td>
                        <td class="px-4 py-2">{{ $fichaje->notas }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</x-app-layout>