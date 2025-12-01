<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">Mi Fichaje</h2>
    </x-slot>

    <div class="py-10 flex flex-col items-center gap-10">

        {{-- MENSAJE DE ÉXITO --}}
        @if(session('success'))
        <div class="bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg text-center max-w-xl">
            {{ session('success') }}
        </div>
        @endif


        {{-- ESTADO ACTUAL --}}
        <p class="text-2xl  text-white font-semibold">
            Estado actual:

            @if($ultimoTipo === 'pausa')
            <span class="text-yellow-500">Pausa</span>
            @elseif($ultimoTipo === 'salida')
            <span class="text-red-500">fin de jornada</span>
            @else
            <span class="text-green-500">Trabajando...</span>
            @endif
        </p>


        {{-- FORMULARIO --}}
        <form method="POST" action="{{ route('fichajes.store') }}" id="formFichaje"
            class="space-y-10 flex flex-col items-center">
            @csrf

            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">

            {{-- BOTÓN ENTRADA --}}
            @if(!$ultimo || $ultimoTipo === 'salida')
            <button type="submit" name="tipo" value="entrada" id="btnEntrada"
                class="w-52 h-52 rounded-full shadow-2xl text-white text-xl 
                   flex flex-col items-center justify-center font-semibold
                   bg-sartu-rojo hover:bg-sartu-rojo-oscuro">
                ENTRADA
            </button>
            @endif

            {{-- BOTÓN PAUSA --}}
            @if($ultimoTipo === 'entrada' || $ultimoTipo === 'reanudar')
            <button type="submit" name="tipo" value="pausa" id="btnPausa"
                class="w-80 py-4 bg-yellow-500 text-white rounded-2xl">
                PAUSA
            </button>
            @endif

            {{-- BOTÓN REANUDAR --}}
            @if($ultimoTipo === 'pausa')
            <button type="submit" name="tipo" value="reanudar" id="btnReanudar"
                class="w-80 py-4 bg-sartu-marron text-white rounded-2xl">
                REANUDAR
            </button>
            @endif

            {{-- BOTÓN SALIDA --}}
            @if($ultimoTipo === 'entrada' || $ultimoTipo === 'reanudar')
            <button type="submit" name="tipo" value="salida" id="btnSalida"
                class="w-52 h-52 rounded-full bg-red-600 text-white">
                SALIDA
            </button>
            @endif
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const form = document.getElementById('formFichaje');
            const latInput = document.getElementById('lat');
            const lngInput = document.getElementById('lng');

            if (!form) return;

            form.querySelectorAll("button[type='submit']").forEach(btn => {

                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    if (!navigator.geolocation) {
                        alert("❌ Tu dispositivo no soporta GPS.");
                        return;
                    }

                    navigator.geolocation.getCurrentPosition(
                        pos => {
                            latInput.value = pos.coords.latitude;
                            lngInput.value = pos.coords.longitude;
                            form.requestSubmit(btn);
                        },
                        err => {
                            alert("❌ Debes activar la ubicación para poder fichar.");
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    );
                });

            });

        });
    </script>

</x-app-layout>
