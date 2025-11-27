@php
use Carbon\Carbon;

$trabajado = $resumen->tiempo_trabajado ?? 0;
$pausas    = $resumen->tiempo_pausas ?? 0;

$jornada   = $empresa->jornada_diaria_minutos ?? 480;
$pausaMax  = $empresa->max_pausa_no_contabilizada ?? 0;

$progreso = $jornada > 0
    ? min(100, round(($trabajado * 100) / $jornada))
    : 0;

$pausaRestante = max(0, $pausaMax - $pausas);

// HORAS EXTRA (solo informativa)
$extraMin = max(0, $trabajado - $jornada);

// ⏱ TIEMPO REAL DESDE EL ÚLTIMO FICHAJE
$segundosBase = 0;

if (
    !empty($ultimoFichaje) &&
    in_array($ultimoTipo, ['entrada', 'reanudar']) &&
    $ultimoFichaje->fecha_hora->isToday()
) {
    $segundosBase = $ultimoFichaje->fecha_hora->diffInSeconds(now());
}
@endphp

<div class="bg-white p-8 rounded-2xl shadow-xl text-center space-y-6">

    <h2 class="text-2xl font-bold text-sartu-negro">
        Mi Jornada de Hoy
    </h2>

    {{-- RELOJ EN TIEMPO REAL (TRABAJO) --}}
    <div
        id="relojTiempoReal"
        class="text-4xl font-mono font-bold text-sartu-rojo"
        data-segundos="{{ $segundosBase }}"
    >
        00:00:00
    </div>

    {{-- ESTADO ACTUAL --}}
    <p class="text-lg font-semibold">
        Estado actual:
        @if($ultimoTipo === 'pausa')
            <span class="text-yellow-500">En pausa</span>
        @elseif($ultimoTipo === 'salida' || empty($ultimoTipo))
            <span class="text-red-500">Fuera</span>
        @else
            <span class="text-green-600">Trabajando</span>
        @endif
    </p>

    {{-- BARRA PROGRESO --}}
    <div class="w-full bg-gray-300 rounded-full h-5 overflow-hidden">
        <div
            id="barraProgreso"
            data-progreso="{{ $progreso }}"
            class="h-5 bg-sartu-rojo transition-all duration-500"
        ></div>
    </div>

    <p class="text-sm text-gray-700">
        Progreso jornada: <strong>{{ $progreso }}%</strong>
    </p>

    {{-- MÉTRICAS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">

        <div class="bg-sartu-rojo text-white p-4 rounded-lg">
            <p class="text-sm uppercase">Trabajado</p>
            <p class="text-2xl font-bold">{{ $trabajado }} min</p>
        </div>

        <div class="bg-sartu-marron text-white p-4 rounded-lg">
            <p class="text-sm uppercase">Pausas</p>
            <p class="text-2xl font-bold">{{ $pausas }} min</p>
        </div>

        <div class="bg-sartu-gris-oscuro text-white p-4 rounded-lg">
            <p class="text-sm uppercase">Pausa restante</p>
            <p class="text-2xl font-bold">{{ $pausaRestante }} min</p>
        </div>

        <div class="bg-black text-white p-4 rounded-lg">
            <p class="text-sm uppercase">Objetivo</p>
            <p class="text-2xl font-bold">{{ $jornada }} min</p>
        </div>
    </div>

    {{-- ALERTA EXCESO PAUSA --}}
    @if($pausas > $pausaMax)
        <div class="bg-red-600 text-white p-3 rounded-lg mt-4">
            ⚠ Has superado el tiempo de pausa permitido
        </div>
    @endif

    {{-- ALERTA HORAS EXTRA --}}
    @if($extraMin > 0)
        <div class="bg-orange-500 text-white p-3 rounded-lg mt-2">
            ⚠ Estás en horas extra (+{{ $extraMin }} min sobre la jornada)
        </div>
    @endif

    {{-- BOTONES --}}
    <form
        method="POST"
        action="{{ route('fichajes.store') }}"
        class="flex flex-wrap gap-6 justify-center mt-6"
    >
        @csrf
        <input type="hidden" name="lat" id="lat">
        <input type="hidden" name="lng" id="lng">

        @if(!$ultimoTipo || $ultimoTipo === 'salida')
            <button
                type="submit"
                name="tipo"
                value="entrada"
                class="px-8 py-4 bg-green-600 text-white rounded-xl text-xl shadow"
            >
                ENTRADA
            </button>
        @endif

        @if($ultimoTipo === 'entrada' || $ultimoTipo === 'reanudar')
            <button
                type="submit"
                name="tipo"
                value="pausa"
                class="px-8 py-4 bg-yellow-500 text-white rounded-xl text-xl shadow"
            >
                PAUSA
            </button>

            <button
                type="submit"
                name="tipo"
                value="salida"
                class="px-8 py-4 bg-red-600 text-white rounded-xl text-xl shadow"
            >
                SALIDA
            </button>
        @endif

        @if($ultimoTipo === 'pausa')
            <button
                type="submit"
                name="tipo"
                value="reanudar"
                class="px-8 py-4 bg-sartu-gris-oscuro text-white rounded-xl text-xl shadow"
            >
                REANUDAR
            </button>
        @endif
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // PROGRESO
    const barra = document.getElementById('barraProgreso');
    if (barra) {
        const progreso = barra.dataset.progreso || 0;
        barra.style.width = progreso + '%';
    }

    // RELOJ TIEMPO REAL (TRABAJO)
    const reloj = document.getElementById('relojTiempoReal');
    let segundos = parseInt(reloj?.dataset?.segundos || 0);

    let estadoActual = "{{ $ultimoTipo ?? '' }}";
    let intervalo = null;

    function pintarReloj() {
        const h = String(Math.floor(segundos / 3600)).padStart(2, '0');
        const m = String(Math.floor((segundos % 3600) / 60)).padStart(2, '0');
        const s = String(segundos % 60).padStart(2, '0');
        reloj.innerText = `${h}:${m}:${s}`;
    }

    function iniciarReloj() {
        if (intervalo) return;
        intervalo = setInterval(() => {
            segundos++;
            pintarReloj();
        }, 1000);
    }

    function pararReloj() {
        if (intervalo) {
            clearInterval(intervalo);
            intervalo = null;
        }
    }

    if (estadoActual === 'entrada' || estadoActual === 'reanudar') {
        iniciarReloj();
    } else {
        pararReloj();
    }

    pintarReloj();

    // GPS
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            pos => {
                const lat = document.getElementById('lat');
                const lng = document.getElementById('lng');
                if (lat && lng) {
                    lat.value = pos.coords.latitude;
                    lng.value = pos.coords.longitude;
                }
            },
            () => {}
        );
    }
});
</script>
