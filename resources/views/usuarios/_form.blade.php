@php
    $modo = isset($usuario) ? 'Editar' : 'Crear';
@endphp

<div class="space-y-4">

    {{-- Nombre --}}
    <div>
        <label class="block text-sartu-negro font-semibold mb-1">Nombre</label>
        <input type="text" name="name"
               class="w-full border rounded px-3 py-2"
               value="{{ old('name', $usuario->name ?? '') }}" required>
        <x-input-error :messages="$errors->get('name')" class="mt-1"/>
    </div>

    {{-- Email --}}
    <div>
        <label class="block text-sartu-negro font-semibold mb-1">Email</label>
        <input type="email" name="email"
               class="w-full border rounded px-3 py-2"
               value="{{ old('email', $usuario->email ?? '') }}" required>
        <x-input-error :messages="$errors->get('email')" class="mt-1"/>
    </div>

    {{-- Empresa --}}
    <div>
        <label class="block text-sartu-negro font-semibold mb-1">Empresa</label>
        <select name="empresa_id" class="w-full border rounded px-3 py-2">
            @foreach($empresas as $empresa)
                <option value="{{ $empresa->id_empresa }}"
                    @selected(old('empresa_id', $usuario->empresa_id ?? '') == $empresa->id_empresa)>
                    {{ $empresa->nombre }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('empresa_id')" class="mt-1"/>
    </div>

    {{-- Rol --}}
    <div>
        <label class="block text-sartu-negro font-semibold mb-1">Rol</label>
        <select name="rol_id" class="w-full border rounded px-3 py-2">
            @foreach($roles as $rol)
                <option value="{{ $rol->id_rol }}"
                    @selected(old('rol_id', $usuario->rol_id ?? '') == $rol->id_rol)>
                    {{ $rol->nombre }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('rol_id')" class="mt-1"/>
    </div>

    {{-- Activo (solo en edición) --}}
    @isset($usuario)
        <div>
            <label class="block text-sartu-negro font-semibold mb-1">Estado</label>
            <select name="activo" class="w-full border rounded px-3 py-2">
                <option value="1" @selected($usuario->activo == 1)>Activo</option>
                <option value="0" @selected($usuario->activo == 0)>Inactivo</option>
            </select>
        </div>
    @endisset

</div>


