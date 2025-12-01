<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Informe de Auditoría</title>
     {{-- CSS DEL PDF --}}
    <style>
        {!! file_get_contents(resource_path('css/pdf.css')) !!}
    </style>
</head>

<body>

    @php
    use App\Models\Usuario;

    $razonSocial = $generadoPor->empresa->nombre ?? 'Empresa no especificada';

    $fromRaw = $filtros['from'] ?? null;
    $toRaw = $filtros['to'] ?? null;

    $from = $fromRaw
    ? \Carbon\Carbon::parse($fromRaw)->format('d/m/Y')
    : 'Todo el período';

    $to = $toRaw
    ? \Carbon\Carbon::parse($toRaw)->format('d/m/Y')
    : 'Hasta la fecha actual';

    $usuario = 'Todos los usuarios';
    if (!empty($filtros['usuario_id'])) {
    $u = Usuario::find($filtros['usuario_id']);
    if ($u) {
    $usuario = $u->name . ' (' . $u->email . ')';
    }
    }

    $accion = !empty($filtros['accion'])
    ? $filtros['accion']
    : 'Todas las acciones';

    $logoEmpresa = $generadoPor->empresa->logo_path
    ? public_path('storage/' . $generadoPor->empresa->logo_path)
    : null;
    @endphp

    <!-- ===== CABECERA FIJA REPETIDA ===== -->
    <div class="pdf-header-fixed">
        <div class="header">

            <table  class="header-table">
                <tr>
                    <td width="110" align="left">
                        <strong class="sartu-rojo">SARTU</strong>
                        <div class="small sartu-gris">Sistema emisor</div>
                    </td>

                    <td align="center" >
                        <div class="titulo">INFORME DE AUDITORÍA DE ACTIVIDAD DEL SISTEMA</div>
                        <div class="subtitulo">Informe generado por la plataforma SARTU</div>
                    </td>

                    <td width="140" align="right">
                        @if($logoEmpresa && file_exists($logoEmpresa))
                        <img src="{{ $logoEmpresa }}" style="height:45px;" alt="LogoEmpresa">
                        @endif
                        <div class="small sartu-gris">Empresa auditada</div>
                    </td>
                </tr>
            </table>

            <div class="meta">
                <strong>Razón social auditada:</strong> {{ $razonSocial }}<br>
                <strong>Sistema:</strong> SARTU – Sistema de Control de Horarios Multiplataforma<br>
                <strong>Fecha de emisión:</strong> {{ $fechaEmision->format('d/m/Y H:i:s') }}<br>
                <strong>Generado por:</strong> {{ $generadoPor->name }}
            </div>
        </div>
    </div>
    <!-- ===== FIN CABECERA FIJA ===== -->

    <!-- ================= TEXTO LEGAL ================= -->
    <div class="legal">
        Este documento refleja los registros de actividad del sistema correspondientes a los filtros seleccionados.
        Los datos aquí contenidos han sido generados automáticamente por el sistema, no han sido modificados manualmente
        y forman parte del libro de auditoría del sistema de control horario. Estos registros tienen validez a efectos
        de trazabilidad interna, control laboral y, en su caso, inspección administrativa.
    </div>

    <!-- ================= FILTROS ================= -->
    <div class="box">
        <div class="box-title">Filtros aplicados</div>

        <table >
            <tr>
                <td><strong>Desde</strong></td>
                <td>{{ $from }}</td>
                <td><strong>Hasta</strong></td>
                <td>{{ $to }}</td>
            </tr>
            <tr>
                <td><strong>Usuario</strong></td>
                <td>{{ $usuario }}</td>
                <td><strong>Acción</strong></td>
                <td>{{ $accion }}</td>
            </tr>
        </table>
    </div>

    <!-- ================= TABLA ================= -->
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acción</th>
                <th>Entidad</th>
                <th>IP</th>
            </tr>
        </thead>

        <tbody>
            @forelse($logs as $log)
            <tr>
                <td>{{ \Carbon\Carbon::parse($log->fecha_creacion)->format('d/m/Y H:i:s') }}</td>
                <td>
                    {{ $log->usuario->name ?? 'Sistema' }}
                    <div class="small">{{ $log->usuario->email ?? '—' }}</div>
                </td>
                <td>{{ $log->usuario->rol->nombre ?? '—' }}</td>
                <td>{{ $log->accion }}</td>
                <td>
                    {{ $log->entidad_tipo ?? '—' }}
                    @if($log->entidad_id)
                    #{{ $log->entidad_id }}
                    @endif
                </td>
                <td>{{ $log->ip }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;">
                    No existen registros para los filtros aplicados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ================= FIRMA ================= -->
    <div class="firma">
        <div class="firma-linea">
            {{ $generadoPor->name }}
            <div class="small">Firma electrónica generada por el sistema</div>
        </div>
    </div>

    <!-- ================= PIE LEGAL ================= -->
    <div class="footer">
        Documento generado automáticamente por el Sistema SARTU.
        Los registros aquí reflejados forman parte del libro de auditoría del sistema.
    </div>

    <!-- ================= NUMERACIÓN LEGAL ================= -->
    <script type="text/php">
        if (isset($pdf)) {
        $text = "Página {PAGE_NUM} / {PAGE_COUNT}";
        $font = $fontMetrics->get_font("DejaVu Sans", "normal");

        $x = 500;
        $y = 820;

        $pdf->page_text($x, $y, $text, $font, 8, array(0,0,0));
    }
</script>

</body>

</html>
