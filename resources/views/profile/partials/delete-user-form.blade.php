<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-red-600">
            Bloquear cuenta
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Al bloquear tu cuenta no podrás volver a acceder al sistema hasta que un administrador la reactive.
        </p>
    </header>

    <form method="POST" action="{{ route('usuarios.bloquear', Auth::user()->id) }}">
        @csrf
        @method('patch')

        <x-danger-button
            onclick="return confirm('¿Seguro que quieres bloquear tu cuenta?');"
        >
            Bloquear mi cuenta
        </x-danger-button>
    </form>
</section>
