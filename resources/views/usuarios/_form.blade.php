@php
$modo = isset($usuario) ? 'Editar' : 'Crear';
$bloqueado = isset($usuario) && $usuario->estado === 'bloqueado';
@endphp

<div class="space-y-4">

    {{-- Nombre --}}
    <div>
        <label for ="name" class="block text-sartu-negro font-semibold mb-1">Nombre</label>
        <input type="text" name="name"
               class="w-full border rounded px-3 py-2"
               value="{{ old('name', $usuario->name ?? '') }}"
               required
               @disabled($bloqueado)>
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    {{-- Email --}}
    <div>
        <label for ="email" class="block text-sartu-negro font-semibold mb-1">Email</label>
        <input type="email" name="email"
               class="w-full border rounded px-3 py-2"
               value="{{ old('email', $usuario->email ?? '') }}"
               required
               @disabled($bloqueado)>
        <x-input-error :messages="$errors->get('email')" class="mt-1" />
    </div>

    {{-- Empresa --}}
    <div>
        <label for ="empresa_id" class="block text-sartu-negro font-semibold mb-1">Empresa</label>
        <select name="empresa_id"
                class="w-full border rounded px-3 py-2"
                @disabled($bloqueado)>
            @foreach($empresas as $empresa)
                <option value="{{ $empresa->id_empresa }}"
                    @selected(old('empresa_id', $usuario->empresa_id ?? '') == $empresa->id_empresa)>
                    {{ $empresa->nombre }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('empresa_id')" class="mt-1" />
    </div>

    {{-- Rol --}}
    <div>
        <label for ="rol_id" class="block text-sartu-negro font-semibold mb-1">Rol</label>
        <select name="rol_id"
                class="w-full border rounded px-3 py-2"
                @disabled($bloqueado)>
            @foreach($roles as $rol)
                <option value="{{ $rol->id_rol }}"
                    @selected(old('rol_id', $usuario->rol_id ?? '') == $rol->id_rol)>
                    {{ $rol->nombre }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('rol_id')" class="mt-1" />
    </div>

    {{-- ESTADO DEL ACCESO (SOLO LECTURA) --}}
    @isset($usuario)
        <div>
            <label for ="estado" class="block text-sartu-negro font-semibold mb-1">
                Estado del acceso
            </label>

            <input type="text"
                   class="w-full border rounded px-3 py-2 bg-gray-100 text-gray-700 cursor-not-allowed"
                   value="{{ strtoupper($usuario->estado) }}"
                   disabled>

            @if($usuario->estado === 'pendiente')
                <p class="text-sm text-yellow-600 mt-1">
                    Este usuario deberá cambiar la contraseña en su próximo acceso.
                </p>
            @elseif($usuario->estado === 'activo')
                <p class="text-sm text-green-600 mt-1">
                    Este usuario tiene acceso normal al sistema.
                </p>
            @elseif($usuario->estado === 'bloqueado')
                <p class="text-sm text-red-600 mt-1">
                    Este usuario está bloqueado. No se puede modificar.
                </p>
            @endif
        </div>
    @endisset

</div>
