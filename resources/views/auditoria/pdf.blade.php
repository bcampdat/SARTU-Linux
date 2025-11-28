<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Auditoría</title>
</head>
<body>

@php
    $razonSocial = $generadoPor->empresa->nombre ?? 'SARTU - Sistema Central';
@endphp

    <div class="header">
        <h1>SARTU <br> INFORME DE AUDITORÍA DE ACTIVIDAD DEL SISTEMA</h1>

        <p><strong>Razón Social:</strong> {{ $razonSocial }}</p>

        <p><strong>Sistema de Control de Horarios Multiplataforma</strong></p>

        <p class="small">
            <strong>Fecha de emisión:</strong>
            {{ $fechaEmision->format('d/m/Y H:i:s') }}
        </p>

        <p class="small">
            <strong>Generado por:</strong>
            {{ $generadoPor->name }} ({{ $generadoPor->rol->nombre }})
        </p>
    </div>

    <div class="legal">
        Este documento refleja los registros de actividad del sistema correspondientes a los filtros
        seleccionados. Los datos aquí contenidos han sido generados automáticamente por el sistema,
        no han sido modificados manualmente y forman parte del libro de auditoría del sistema de control
        horario. Estos registros tienen validez a efectos de trazabilidad interna, control laboral y,
        en su caso, inspección administrativa.
    </div>

    <div class="meta">
        <p><strong>Filtros aplicados:</strong></p>
        <ul class="small">
            <li>Desde: {{ $filtros['from'] ?? '—' }}</li>
            <li>Hasta: {{ $filtros['to'] ?? '—' }}</li>
            <li>Usuario ID: {{ $filtros['usuario_id'] ?? '—' }}</li>
            <li>Acción: {{ $filtros['accion'] ?? '—' }}</li>
        </ul>
    </div>

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
                        {{ $log->usuario->name ?? '—' }}
                        <div class="small">
                            {{ $log->usuario->email ?? 'sistema' }}
                        </div>
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
                        No hay registros para los filtros seleccionados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="firma">
        <div class="small">
            Firma electrónica simulada del sistema
        </div>

        <div class="firma-linea">
            {{ $generadoPor->name }}
        </div>
    </div>

    <div class="footer">
        Documento generado automáticamente por el Sistema de Control de Horarios Multiplataforma (SARTU).
        Los registros aquí reflejados forman parte del libro de auditoría de actividad del sistema.
    </div>

</body>
</html>
