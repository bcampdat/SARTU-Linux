<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-sartu-white">
            Auditoría del sistema
        </h2>
    </x-slot>

    <div class="py-6 space-y-4">

        {{-- FILTROS --}}
        <form method="GET" action="{{ route('auditoria.index') }}"
            class="bg-white p-4 rounded shadow flex flex-nowrap gap-4 items-end">

            {{-- EMPRESA (SOLO ADMIN) --}}
            @if(auth()->user()->rol->nombre === 'admin_sistema')
            <div>
                <label for ="empresa_id" class="block text-sm font-semibold">Empresa</label>
                <select name="empresa_id"
                    class="border rounded px-2 py-1 w-48"
                    onchange="this.form.submit()">
                    <option value="">— — Todas — —</option>

                    @foreach($empresas as $empresa)
                    <option value="{{ $empresa->id_empresa }}"
                        @selected(request('empresa_id')==$empresa->id_empresa)>
                        {{ $empresa->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div>
                <label for ="from" class="block text-sm font-semibold">Desde</label>
                <input type="date" name="from"
                    value="{{ request('from') }}"
                    class="border rounded px-2 py-1">
            </div>

            <div>
                <label for ="to" class="block text-sm font-semibold">Hasta</label>
                <input type="date" name="to"
                    value="{{ request('to') }}"
                    class="border rounded px-2 py-1">
            </div>

            <div>
                <label for ="accion" class="block text-sm font-semibold">Acción</label>
                <input type="text" name="accion"
                    value="{{ request('accion') }}"
                    class="border rounded px-2 py-1"
                    placeholder="login_correcto, fichaje_entrada...">
            </div>

            <div>
                <label for ="usuario_id" class="block text-sm font-semibold">Usuario (email)</label>
                <select name="usuario_id" class="border rounded px-2 py-1 w-56">
                    <option value="">Seleccionar usuario</option>

                    @foreach($usuariosFiltro as $u)
                    <option value="{{ $u->id }}"
                        @selected(request('usuario_id')==$u->id)>
                        {{ $u->email }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-4 py-2 flex items-center justify-center h-[38px] bg-sartu-rojo text-white rounded">
                    Filtrar
                </button>

                <a href="{{ route('auditoria.export.pdf', request()->query()) }}"
                    class="px-4 py-2 flex items-center justify-center h-[38px] bg-sartu-marron text-white rounded">
                    PDF
                </a>
            </div>
        </form>

        {{-- TABLA --}}
        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-sartu-marron text-white">
                    <tr>
                        <th class="px-3 py-2 text-left">Fecha</th>
                        <th class="px-3 py-2 text-left">Usuario</th>
                        <th class="px-3 py-2 text-left">Rol</th>
                        <th class="px-3 py-2 text-left">Acción</th>
                        <th class="px-3 py-2 text-left">Entidad</th>
                        <th class="px-3 py-2 text-left">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2">{{ $log->fecha_creacion }}</td>
                        <td class="px-3 py-2">
                            {{ $log->usuario->name ?? '—' }}
                            <span class="text-xs text-gray-500">
                                ({{ $log->usuario->email ?? 'sistema' }})
                            </span>
                        </td>
                        <td class="px-3 py-2">
                            {{ $log->usuario->rol->nombre ?? '—' }}
                        </td>
                        <td class="px-3 py-2">{{ $log->accion }}</td>
                        <td class="px-3 py-2">
                            {{ $log->entidad_tipo ?? '—' }}
                            @if($log->entidad_id)
                            #{{ $log->entidad_id }}
                            @endif
                        </td>
                        <td class="px-3 py-2">{{ $log->ip }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-3 py-4 text-center text-gray-500">
                            No hay registros que coincidan con los filtros.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $logs->links() }}
        </div>
    </div>
</x-app-layout>
