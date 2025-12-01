<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Información del perfil
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Actualiza tu nombre y tu foto de perfil. El correo electrónico no puede modificarse.
        </p>
    </header>

    <form method="post"
          action="{{ route('profile.update') }}"
          class="mt-6 space-y-6"
          enctype="multipart/form-data">
        @csrf
        @method('put')

        {{-- AVATAR --}}
        <div>
            <x-input-label for="avatar" value="Foto de perfil" />

            <input type="file"
                name="avatar"
                id="avatar"
                accept="image/*"
                class="mt-1 block w-full border rounded px-3 py-2" />

            @if ($user->avatar)
                <img src="{{ asset('storage/'.$user->avatar) }}"
                     class="mt-3 w-24 h-24 rounded-full object-cover border" alt ="Foto de perfil">
            @else
                <img src="{{ asset('images/avatar.png') }}"
                     class="mt-3 w-24 h-24 rounded-full object-cover border" alt ="FotoPerfil">
            @endif

            <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
        </div>

        {{-- NOMBRE --}}
        <div>
            <x-input-label for="name" value="Nombre" />
            <x-text-input id="name"
                          name="name"
                          type="text"
                          class="mt-1 block w-full"
                          :value="old('name', $user->name)"
                          required
                          autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- EMAIL (SOLO LECTURA) --}}
        <div>
            <x-input-label for="email" value="Correo electrónico (no modificable)" />
            <x-text-input id="email"
                          type="email"
                          class="mt-1 block w-full bg-gray-100 text-gray-500 cursor-not-allowed"
                          :value="$user->email"
                          disabled />
        </div>

        {{-- BOTÓN --}}
        <div class="flex items-center gap-4">

            <x-primary-button :disabled="$user->estado === 'bloqueado'">
                Guardar cambios
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Perfil actualizado correctamente.
                </p>
            @endif

        </div>
    </form>
</section>
