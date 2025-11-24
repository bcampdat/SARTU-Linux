<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">Mi Fichaje</h2>
    </x-slot>

    <div class="py-10 text-center">

        @if(session('success'))
            <div class="bg-green-500 text-white px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <p class="text-xl mb-6">
            Estado actual:
            @if($estado === 'trabajando')
                <span class="text-green-400 font-bold">Dentro</span>
            @else
                <span class="text-red-400 font-bold">Fuera</span>
            @endif
        </p>

        <form method="POST" action="{{ route('fichajes.store') }}">
            @csrf

            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">

            <button type="submit"
                class="px-8 py-4 bg-sartu-rojo text-white rounded-xl text-2xl shadow hover:bg-sartu-rojo-oscuro">
                {{ $estado === 'trabajando' ? 'Fichar Salida' : 'Fichar Entrada' }}
            </button>
        </form>
    </div>

    <script>
        navigator.geolocation.getCurrentPosition(
            pos => {
                document.getElementById('lat').value = pos.coords.latitude;
                document.getElementById('lng').value = pos.coords.longitude;
            }
        );
    </script>

</x-app-layout>
