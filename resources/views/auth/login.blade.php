<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-sartu-rojo">Iniciar Sesión</h2>
        <p class="mt-2 text-sartu-negro">Accede a tu cuenta SARTU</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-sartu-white mb-2">
                Correo Electrónico
            </label>
            <input
                id="email"
                name="email"
                type="email"
                required
                autofocus
                autocomplete="email"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sartu-rojo focus:border-transparent transition duration-200"
                placeholder="usuario@empresa.com"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-sartu-white mb-2">
                Contraseña
            </label>
            <input
                id="password"
                name="password"
                type="password"
                required
                autocomplete="current-password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sartu-rojo focus:border-transparent transition duration-200"
                placeholder="••••••••"
            >
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mb-6">
            <label for="remember_me" class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded border-gray-300 text-sartu-rojo focus:ring-sartu-rojo">
                <span class="ms-2 text-sm text-sartu-negro">Recordar sesión</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-sartu-rojo hover:text-sartu-rojo-oscuro">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <button type="submit"
                class="w-full bg-sartu-rojo text-white py-3 px-4 rounded-lg hover:bg-sartu-rojo-oscuro focus:ring-4 focus:ring-sartu-rojo/20 transition duration-200 font-semibold">
            Iniciar Sesión
        </button>
    </form>
</x-guest-layout>

