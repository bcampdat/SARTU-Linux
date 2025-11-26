<div class="max-w-7xl mx-auto space-y-10">

    {{-- MI FICHAJE (MISMO BLOQUE QUE EMPLEADO) --}}
    @include('fichajes._miFichaje', [
    'resumen' => $miResumen,
    'ultimoTipo' => optional($misUltimosFichajes->first())->tipo,
    'empresa' => auth()->user()->empresa,
    'ultimoFichaje' => $misUltimosFichajes->first()
    ])


    {{-- MÉTRICAS EMPRESA --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-sartu-rojo text-white p-6 rounded-xl text-center">
            <p>Total empleados</p>
            <p class="text-4xl font-bold">{{ $empleadosTotal }}</p>
        </div>

        <div class="bg-sartu-marron text-white p-6 rounded-xl text-center">
            <p>Activos</p>
            <p class="text-4xl font-bold">{{ $totalUsuariosActivos }}</p>
        </div>

        <div class="bg-sartu-gris-oscuro text-white p-6 rounded-xl text-center">
            <p>Entradas hoy</p>
            <p class="text-4xl font-bold">{{ $totalEntradasHoy }}</p>
        </div>

        <div class="bg-sartu-negro text-white p-6 rounded-xl text-center">
            <p>Salidas hoy</p>
            <p class="text-4xl font-bold">{{ $totalSalidasHoy }}</p>
        </div>

    </div>

</div>
