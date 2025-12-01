<div class="space-y-4">

    {{-- Logo + Nombre --}}
    <div class="grid grid-cols-1 md:grid-cols-6 gap-6 items-center">

        {{-- Logo --}}
        <div class="md:col-span-1 text-center">
            <label  for ="logo" class="block font-semibold text-sartu-negro mb-1">
                Logo
            </label>

            <input type="file"
                id="logo-input"
                name="logo"
                accept="image/png,image/jpeg"
                class="w-full border rounded px-2 py-1 text-sm">

            <p id="logo-filename" class="mt-1 text-xs text-gray-600 hidden"></p>

            <p class="mt-1 text-xs text-gray-500">
                Formatos permitidos: PNG, JPG, JPEG · Máx 2MB
            </p>

            <x-input-error :messages="$errors->get('logo')" class="mt-1" />

            @if(isset($empresa) && $empresa->logo_thumb)
                <div class="mt-2 flex justify-center">
                    <img src="{{ asset('storage/' . $empresa->logo_thumb) }}"
                        class="h-12 rounded shadow" alt="Logo de la empresa">
                </div>
            @endif
        </div>

        {{-- Nombre de la empresa --}}
        <div class="md:col-span-4">
            <label for="nombre" class="block font-semibold text-sartu-negro mb-1">
                Nombre de la empresa
            </label>

            <input type="text"
                name="nombre"
                value="{{ old('nombre', $empresa->nombre ?? '') }}"
                class="w-full border rounded px-3 py-2"
                required>

            <x-input-error :messages="$errors->get('nombre')" class="mt-1" />
        </div>

    </div>

    {{-- Límite Usuarios --}}
    <div>
        <label for ="limite_usuarios" class="block font-semibold text-sartu-negro mb-1">
            Límite de Usuarios
        </label>

        <input type="number" name="limite_usuarios"
            value="{{ old('limite_usuarios', $empresa->limite_usuarios ?? '') }}"
            class="w-full border rounded px-3 py-2" min="1" required>

        <x-input-error :messages="$errors->get('limite_usuarios')" class="mt-1" />
    </div>

    {{-- Jornada Diaria (minutos) --}}
    <div>
        <label for ="jornada_diaria_minutos" class="block font-semibold text-sartu-negro mb-1">
            Jornada diaria (minutos)
        </label>

        <input type="number" name="jornada_diaria_minutos"
            value="{{ old('jornada_diaria_minutos', $empresa->jornada_diaria_minutos ?? '') }}"
            class="w-full border rounded px-3 py-2" min="1" required>

        <x-input-error :messages="$errors->get('jornada_diaria_minutos')" class="mt-1" />
    </div>

    {{-- Máx. pausa no contabilizada (minutos) --}}
    <div>
        <label for="max_pausa_no_contabilizada" class="block font-semibold text-sartu-negro mb-1">
            Máximo de pausa no contabilizada (minutos)
        </label>

        <input type="number" name="max_pausa_no_contabilizada"
            value="{{ old('max_pausa_no_contabilizada', $empresa->max_pausa_no_contabilizada ?? '') }}"
            class="w-full border rounded px-3 py-2" min="0" required>

        <x-input-error :messages="$errors->get('max_pausa_no_contabilizada')" class="mt-1" />
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const input = document.getElementById('logo-input');
    const filename = document.getElementById('logo-filename');

    const allowedTypes = [
        'image/png',
        'image/jpeg'
    ];

    const maxSize = 2 * 1024 * 1024; // 2MB

    if (!input || !filename) return;

    input.addEventListener('change', () => {
        const file = input.files[0];
        if (!file) return;

        //  Tipo no permitido
        if (!allowedTypes.includes(file.type)) {
            filename.textContent = '❌ Archivo no soportado. Usa PNG o JPG.';
            filename.classList.remove('hidden');
            filename.classList.remove('text-green-600');
            filename.classList.add('text-red-600');
            input.value = '';
            return;
        }

        // Tamaño excesivo
        if (file.size > maxSize) {
            filename.textContent = '❌ El archivo supera el tamaño máximo de 2MB.';
            filename.classList.remove('hidden');
            filename.classList.remove('text-green-600');
            filename.classList.add('text-red-600');
            input.value = '';
            return;
        }

        // Archivo correcto
        filename.textContent = '✅ Archivo seleccionado: ' + file.name;
        filename.classList.remove('hidden');
        filename.classList.remove('text-red-600');
        filename.classList.add('text-green-600');
    });

});
</script>
