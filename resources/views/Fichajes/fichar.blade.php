<form action="{{ route('fichajes.store') }}" method="POST">
    @csrf

    @php
        $ultimoTipo = $ultimo->tipo ?? null;
    @endphp

    {{-- ENTRADA → si no ha fichado aún o si terminó el día --}}
    @if(!$ultimo || $ultimoTipo === 'salida')
        <input type="hidden" name="tipo" value="entrada">
        <button class="w-full px-4 py-3 bg-green-600 text-white rounded-lg text-lg mb-4">
            🟢 Fichar ENTRADA
        </button>
    @endif

    {{-- PAUSA (comida/café) --}}
    @if($ultimoTipo === 'entrada' || $ultimoTipo === 'reanudar')
        <input type="hidden" name="tipo" value="pausa">
        <button class="w-full px-4 py-3 bg-yellow-500 text-white rounded-lg text-lg mb-4">
            🟡 Iniciar PAUSA / COMIDA
        </button>
    @endif

    {{-- REANUDAR trabajo --}}
    @if($ultimoTipo === 'pausa')
        <input type="hidden" name="tipo" value="reanudar">
        <button class="w-full px-4 py-3 bg-blue-500 text-white rounded-lg text-lg mb-4">
            🔵 Volver de PAUSA
        </button>
    @endif

    {{-- SALIDA --}}
    @if($ultimoTipo === 'entrada' || $ultimoTipo === 'reanudar')
        <input type="hidden" name="tipo" value="salida">
        <button class="w-full px-4 py-3 bg-red-600 text-white rounded-lg text-lg">
            🔴 Fichar SALIDA
        </button>
    @endif

</form>
