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

// SEGUNDOS BASE DESDE BACKEND
$segundosTrabajoBase = $trabajado * 60;
$segundosPausaBase   = $pausas * 60;

$segundosTrabajoActual = $segundosTrabajoBase;
$segundosPausaActual   = $segundosPausaBase;

if (!empty($ultimoFichaje) && $ultimoFichaje->fecha_hora->isToday()) {
    $diff = $ultimoFichaje->fecha_hora->diffInSeconds(now());

    if (in_array($ultimoTipo, ['entrada', 'reanudar'])) {
        $segundosTrabajoActual += $diff;
    }

    if ($ultimoTipo === 'pausa') {
        $segundosPausaActual += $diff;
    }
}
@endphp

<div class="bg-white p-8 rounded-2xl shadow-xl text-center space-y-6">

    <h2 class="text-2xl font-bold text-sartu-negro">
        Mi Jornada de Hoy
    </h2>

    {{-- RELOJ TRABAJO --}}
    <div
        id="relojTrabajo"
        class="text-4xl font-mono font-bold text-sartu-rojo"
        data-segundos="{{ $segundosTrabajoActual }}"
    >00:00:00</div>

    {{-- RELOJ PAUSA --}}
    <div
        id="relojPausa"
        class="text-xl font-mono font-bold text-yellow-500"
        data-segundos="{{ $segundosPausaActual }}"
    >00:00:00</div>

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

    {{-- ALERTAS --}}
    @if($pausas > $pausaMax)
        <div class="bg-red-600 text-white p-3 rounded-lg mt-4">
            ⚠ Has superado el tiempo de pausa permitido
        </div>
    @endif

    @if($extraMin > 0)
        <div class="bg-orange-500 text-white p-3 rounded-lg mt-2">
            ⚠ Estás en horas extra (+{{ $extraMin }} min sobre la jornada)
        </div>
    @endif

    {{-- BOTONES --}}
    <form method="POST" action="{{ route('fichajes.store') }}" class="flex flex-wrap gap-6 justify-center mt-6">
        @csrf
        <input type="hidden" name="lat" id="lat">
        <input type="hidden" name="lng" id="lng">

        @if(!$ultimoTipo || $ultimoTipo === 'salida')
            <button type="submit" name="tipo" value="entrada"
                class="px-8 py-4 bg-green-600 text-white rounded-xl text-xl shadow">
                ENTRADA
            </button>
        @endif

        @if($ultimoTipo === 'entrada' || $ultimoTipo === 'reanudar')
            <button type="submit" name="tipo" value="pausa"
                class="px-8 py-4 bg-yellow-500 text-white rounded-xl text-xl shadow">
                PAUSA
            </button>

            <button type="submit" name="tipo" value="salida"
                class="px-8 py-4 bg-red-600 text-white rounded-xl text-xl shadow">
                SALIDA
            </button>
        @endif

        @if($ultimoTipo === 'pausa')
            <button type="submit" name="tipo" value="reanudar"
                class="px-8 py-4 bg-sartu-gris-oscuro text-white rounded-xl text-xl shadow">
                REANUDAR
            </button>
        @endif
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const barra = document.getElementById('barraProgreso');
    if (barra) barra.style.width = (barra.dataset.progreso || 0) + '%';

    const relojTrabajo = document.getElementById('relojTrabajo');
    const relojPausa   = document.getElementById('relojPausa');

    let segundosTrabajo = parseInt(relojTrabajo.dataset.segundos || 0);
    let segundosPausa   = parseInt(relojPausa.dataset.segundos || 0);

    let estadoActual = "{{ $ultimoTipo ?? '' }}";
    let intervaloTrabajo = null;
    let intervaloPausa   = null;

    function formato(seg) {
        const h = String(Math.floor(seg / 3600)).padStart(2, '0');
        const m = String(Math.floor((seg % 3600) / 60)).padStart(2, '0');
        const s = String(seg % 60).padStart(2, '0');
        return `${h}:${m}:${s}`;
    }

    function pintar() {
        relojTrabajo.innerText = formato(segundosTrabajo);
        relojPausa.innerText   = formato(segundosPausa);
    }

    function iniciarTrabajo() {
        if (intervaloTrabajo) return;
        intervaloTrabajo = setInterval(() => {
            segundosTrabajo++;
            pintar();
        }, 1000);
    }

    function iniciarPausa() {
        if (intervaloPausa) return;
        intervaloPausa = setInterval(() => {
            segundosPausa++;
            pintar();
        }, 1000);
    }

    function pararTodo() {
        clearInterval(intervaloTrabajo);
        clearInterval(intervaloPausa);
        intervaloTrabajo = null;
        intervaloPausa   = null;
    }

    if (estadoActual === 'entrada' || estadoActual === 'reanudar') iniciarTrabajo();
    if (estadoActual === 'pausa') iniciarPausa();

    pintar();
});
</script>
