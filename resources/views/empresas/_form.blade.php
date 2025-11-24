<div class="space-y-4">

    {{-- Nombre --}}
    <div>
        <label class="block font-semibold text-sartu-negro mb-1">Nombre</label>
        <input type="text" name="nombre"
               value="{{ old('nombre', $empresa->nombre ?? '') }}"
               class="w-full border rounded px-3 py-2" required>
        <x-input-error :messages="$errors->get('nombre')" class="mt-1"/>
    </div>

    {{-- Límite Usuarios --}}
    <div>
        <label class="block font-semibold text-sartu-negro mb-1">Límite de Usuarios</label>
        <input type="number" name="limite_usuarios"
               value="{{ old('limite_usuarios', $empresa->limite_usuarios ?? '') }}"
               class="w-full border rounded px-3 py-2" min="1" required>
        <x-input-error :messages="$errors->get('limite_usuarios')" class="mt-1"/>
    </div>

</div>
