<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Crear Usuario</h2>
    </x-slot>

    <div class="py-6">
        <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for ="nombre">Nombre</label>
                <input type="text" name="nombre" class="border rounded px-2 py-1 w-full" required>
            </div>
            <div>
                <label for ="email">Email</label>
                <input type="email" name="email" class="border rounded px-2 py-1 w-full" required>
            </div>
            <div>
                <label for="password">Contraseña</label>
                <input type="password" name="password" class="border rounded px-2 py-1 w-full" required>
            </div>
            <div>
                <label for="id_rol">Rol</label>
                <select name="id_rol" class="border rounded px-2 py-1 w-full" required>
                    @foreach ($roles as $rol)
                        <option value="{{ $rol->id_rol }}">{{ $rol->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">Crear Usuario</button>
        </form>
    </div>
</x-app-layout>
