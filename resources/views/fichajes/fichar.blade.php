<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white">Mi Fichaje</h2>
    </x-slot>

    @if($resumen && $resumen->alerta_pausa)
    <div class="bg-yellow-500 text-white px-6 py-3 rounded-lg shadow-lg text-center max-w-lg">
        ⚠ Has superado el máximo de pausa permitido.
    </div>
    @endif

    @if($resumen && $resumen->alerta_jornada)
    <div class="bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg text-center max-w-lg">
        ⚠ Has superado la jornada máxima.
    </div>
    @endif


    <div class="py-10 flex flex-col items-center gap-10">

        {{-- MENSAJE DE ÉXITO --}}
        @if(session('success'))
        <div class="bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg text-center max-w-xl">
            {{ session('success') }}
        </div>
        @endif

        {{-- ESTADO ACTUAL --}}
        <p class="text-2xl font-semibold">
            Estado actual:

            @if($ultimoTipo === 'pausa')
            <span class="text-yellow-500">Pausa</span>
            @elseif($ultimoTipo === 'salida')
            <span class="text-red-500">Fin de jornada</span>
            @else
            <span class="text-green-500">Trabajando...</span>
            @endif
        </p>


        {{-- FORMULARIO --}}
        <form method="POST" action="{{ route('fichajes.store') }}" id="formFichaje"
            class="space-y-10 flex flex-col items-center">
            @csrf

            {{-- GPS --}}
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">

            {{-- METODO (estrategia) --}}
            <input type="hidden" name="metodo" id="metodo" value="web">

            {{-- AQUÍ EL ÚNICO TIPO --}}
            <input type="hidden" name="tipo" id="tipo">

            {{-- BOTÓN ENTRADA --}}
            @if(!$ultimo || $ultimoTipo === 'salida')
            <button id="btnEntrada" type="button"
                onclick="setTipo('entrada')"
                class="w-52 h-52 rounded-full shadow-2xl text-white text-xl
                   flex flex-col items-center justify-center font-semibold
                   bg-sartu-rojo hover:bg-sartu-rojo-oscuro
                   transition transform hover:scale-105 active:scale-95 disabled:opacity-40">
                <span class="text-5xl mb-2">⬤</span>
                ENTRADA
            </button>
            @endif


            {{-- BOTÓN PAUSA --}}
            @if($ultimoTipo === 'entrada' || $ultimoTipo === 'reanudar')
            <button id="btnPausa" type="button"
                onclick="setTipo('pausa')"
                class="w-80 py-4 bg-yellow-500 hover:bg-yellow-600 text-white
                   rounded-2xl shadow-lg font-semibold text-xl
                   flex justify-center items-center gap-3
                   transition transform hover:scale-105 active:scale-95 disabled:opacity-40">
                <span class="w-4 h-4 bg-white border-4 border-yellow-800 rounded-full"></span>
                PAUSA / COMIDA
            </button>
            @endif


            {{-- BOTÓN REANUDAR --}}
            @if($ultimoTipo === 'pausa')
            <button id="btnReanudar" type="button"
                onclick="setTipo('reanudar')"
                class="w-80 py-4 bg-sartu-marron text-white rounded-2xl shadow-lg
                   font-semibold text-xl transition transform hover:scale-105
                   active:scale-95 hover:bg-sartu-gris-oscuro disabled:opacity-40">
                🔄 Reanudar Trabajo
            </button>
            @endif


            {{-- BOTÓN SALIDA --}}
            @if($ultimoTipo === 'entrada' || $ultimoTipo === 'reanudar')
            <button id="btnSalida" type="button"
                onclick="setTipo('salida')"
                class="w-52 h-52 rounded-full shadow-2xl text-white text-xl
                   flex flex-col items-center justify-center font-semibold
                   bg-red-600 hover:bg-red-700
                   transition transform hover:scale-105 active:scale-95 disabled:opacity-40">
                <span class="text-5xl mb-2">⬤</span>
                SALIDA
            </button>
            @endif

        </form>
    </div>

    {{-- SCRIPT GPS + ASIGNAR TIPO --}}
    <script>
        const btns = document.querySelectorAll("button[id^='btn']");
        const latInput = document.getElementById('lat');
        const lngInput = document.getElementById('lng');
        const form = document.getElementById('formFichaje');
        const tipoInput = document.getElementById('tipo');

        function setTipo(valor) {
            // exigir GPS: si no hay coordenadas, impedir envío
            if (!latInput.value || !lngInput.value) {
                alert("⚠ Debes permitir el acceso al GPS para poder fichar.");
                return;
            }
            tipoInput.value = valor;
            form.submit();
        }

        // Desactivar botones hasta obtener el GPS
        btns.forEach(b => b.disabled = true);

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                pos => {
                    latInput.value = pos.coords.latitude;
                    lngInput.value = pos.coords.longitude;

                    // Activar botones solo si tenemos coords válidas
                    if (latInput.value && lngInput.value) {
                        btns.forEach(b => b.disabled = false);
                    }
                },
                err => {
                    // Mantener botones desactivados y avisar claramente
                    console.warn('Geolocation error', err);
                    alert("⚠ Debes permitir el acceso al GPS para poder fichar. Si no lo permites no podrás fichar desde este dispositivo.");
                    btns.forEach(b => b.disabled = true);
                }, {
                    timeout: 10000,
                    enableHighAccuracy: true
                }
            );
        } else {
            alert("⚠ Tu dispositivo no soporta geolocalización. No es posible fichar.");
            btns.forEach(b => b.disabled = true);
        }
    </script>

</x-app-layout>
