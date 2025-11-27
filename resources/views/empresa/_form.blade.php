<div class="space-y-4">

    {{-- Nombre --}}
    <div>
        <label for="nombre" class="block font-semibold text-sartu-negro mb-1">Nombre</label>
        <input type="text" name="nombre"
               value="{{ old('nombre', $empresa->nombre ?? '') }}"
               class="w-full border rounded px-3 py-2" required>
        <x-input-error :messages="$errors->get('nombre')" class="mt-1"/>
    </div>

    {{-- Límite Usuarios --}}
    <div>
        <label for="limite_usuarios" class="block font-semibold text-sartu-negro mb-1">Límite de Usuarios</label>
        <input type="number" name="limite_usuarios"
               value="{{ old('limite_usuarios', $empresa->limite_usuarios ?? '') }}"
               class="w-full border rounded px-3 py-2" min="1" required>
        <x-input-error :messages="$errors->get('limite_usuarios')" class="mt-1"/>
    </div>

    {{-- Jornada Diaria (minutos) --}}
    <div>
        <label for="jornada_diaria_minutos" class="block font-semibold text-sartu-negro mb-1">
            Jornada diaria (minutos)
        </label>
        <input type="number" name="jornada_diaria_minutos"
               value="{{ old('jornada_diaria_minutos', $empresa->jornada_diaria_minutos ?? '') }}"
               class="w-full border rounded px-3 py-2" min="1" required>
        <x-input-error :messages="$errors->get('jornada_diaria_minutos')" class="mt-1"/>
    </div>

    {{-- Máx. pausa no contabilizada (minutos) --}}
    <div>
        <label for="max_pausa_no_contabilizada" class="block font-semibold text-sartu-negro mb-1">
            Máximo de pausa no contabilizada (minutos)
        </label>
        <input type="number" name="max_pausa_no_contabilizada"
               value="{{ old('max_pausa_no_contabilizada', $empresa->max_pausa_no_contabilizada ?? '') }}"
               class="w-full border rounded px-3 py-2" min="0" required>
        <x-input-error :messages="$errors->get('max_pausa_no_contabilizada')" class="mt-1"/>
    </div>

</div>
