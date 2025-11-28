<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Auditoría</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 15px; }
        .header h1 { font-size: 16px; margin: 0; }
        .header p { margin: 2px 0; }
        .meta, .firma { margin-top: 10px; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #444; padding: 4px; }
        th { background: #ddd; }
        .small { font-size: 9px; }
        .firma { margin-top: 40px; text-align: right; }
        .firma-linea {
            margin-top: 40px;
            border-top: 1px solid #000;
            width: 220px;
            float: right;
            text-align: center;
            padding-top: 3px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
        }
        .legal {
            margin-top: 12px;
            font-size: 9px;
            text-align: justify;
        }
    </style>
</head>
<body>

@php
    $razonSocial = $generadoPor->empresa->nombre ?? 'SARTU - Sistema Central';
@endphp

    <div class="header">
        <h1>INFORME DE AUDITORÍA DE ACTIVIDAD DEL SISTEMA</h1>

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
            <div class="small">
                {{ $generadoPor->rol->nombre }}
            </div>
        </div>
    </div>

    <div class="footer">
        Documento generado automáticamente por el Sistema de Control de Horarios Multiplataforma (SARTU).
        Los registros aquí reflejados forman parte del libro de auditoría de actividad del sistema.
    </div>

</body>
</html>
