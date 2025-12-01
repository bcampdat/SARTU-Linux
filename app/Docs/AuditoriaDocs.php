<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * ################ MODELO ################
 *
 * @OA\Schema(
 *     schema="Auditoria",
 *     type="object",
 *     title="Auditoria",
 *     description="Registro de auditoría",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="accion", type="string"),
 *     @OA\Property(property="usuario_id", type="integer"),
 *     @OA\Property(property="empresa_id", type="integer"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Get(
 *     path="/api/auditorias",
 *     summary="Listar auditorías",
 *     description="Devuelve un listado paginado de auditorías, filtrable por fecha, acción, usuario y empresa según el rol del usuario.",
 *     tags={"Auditoria"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="from",
 *         in="query",
 *         description="Fecha de inicio (YYYY-MM-DD)",
 *         required=false,
 *         @OA\Schema(type="string", format="date")
 *     ),
 *     @OA\Parameter(
 *         name="to",
 *         in="query",
 *         description="Fecha de fin (YYYY-MM-DD)",
 *         required=false,
 *         @OA\Schema(type="string", format="date")
 *     ),
 *     @OA\Parameter(
 *         name="accion",
 *         in="query",
 *         description="Filtrar por acción",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="usuario_id",
 *         in="query",
 *         description="Filtrar por usuario",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="empresa_id",
 *         in="query",
 *         description="Filtrar por empresa (solo admin_sistema)",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Listado paginado de auditorías",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Auditoria")),
 *             @OA\Property(property="links", type="object"),
 *             @OA\Property(property="meta", type="object")
 *         )
 *     ),
 *     @OA\Response(response=403, description="No autorizado")
 * )
 */

/**
 * @OA\Get(
 *     path="/api/auditorias/export",
 *     summary="Exportar auditorías",
 *     description="Devuelve todos los registros de auditoría según filtros, con info de usuario que genera la exportación.",
 *     tags={"Auditoria"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="from",
 *         in="query",
 *         description="Fecha de inicio (YYYY-MM-DD)",
 *         required=false,
 *         @OA\Schema(type="string", format="date")
 *     ),
 *     @OA\Parameter(
 *         name="to",
 *         in="query",
 *         description="Fecha de fin (YYYY-MM-DD)",
 *         required=false,
 *         @OA\Schema(type="string", format="date")
 *     ),
 *     @OA\Parameter(
 *         name="accion",
 *         in="query",
 *         description="Filtrar por acción",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="usuario_id",
 *         in="query",
 *         description="Filtrar por usuario",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="empresa_id",
 *         in="query",
 *         description="Filtrar por empresa (solo admin_sistema)",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Exportación de auditorías",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="generado_por", ref="#/components/schemas/Usuario"),
 *             @OA\Property(property="fecha", type="string", format="date-time"),
 *             @OA\Property(property="filtros", type="object"),
 *             @OA\Property(property="logs", type="array", @OA\Items(ref="#/components/schemas/Auditoria"))
 *         )
 *     ),
 *     @OA\Response(response=403, description="No autorizado")
 * )
 */
class AuditoriaDocs {}
