<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-sartu-white">Cambio de Contraseña Obligatorio</h2>

        @if(session('info'))
            <div class="mt-3 text-blue-600">
                {{ session('info') }}
            </div>
        @endif
    </div>

    <form method="POST" action="{{ route('password.force-change.update') }}">
        @csrf

        <div class="mb-6">
            <label for ="password" class="block text-sm font-medium text-sartu-white mb-2">
                Nueva contraseña
            </label>
            <input type="password" name="password" class="w-full px-4 py-3 border rounded-lg" required>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mb-6">
            <label for ="password_confirmation" class="block text-sm font-medium text-sartu-white mb-2">
                Confirmar contraseña
            </label>
            <input type="password" name="password_confirmation" class="w-full px-4 py-3 border rounded-lg" required>
        </div>

        <button type="submit"
                class="w-full bg-sartu-rojo text-white py-3 px-4 rounded-lg">
            Guardar Contraseña
        </button>
    </form>
</x-guest-layout>
