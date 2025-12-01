<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * ################ ENDPOINTS FICHAJE ################
 *
 * @OA\Post(
 *     path="/api/fichajes/fichar",
 *     summary="Realizar fichaje",
 *     description="Permite registrar un fichaje (entrada, salida, pausa o reanudar) con lat/lng y notas opcionales.",
 *     tags={"Fichaje"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="tipo", type="string", enum={"entrada","salida","pausa","reanudar"}),
 *             @OA\Property(property="metodo", type="string", enum={"web","pwa","nfc","pc"}, nullable=true),
 *             @OA\Property(property="lat", type="number", format="float"),
 *             @OA\Property(property="lng", type="number", format="float"),
 *             @OA\Property(property="notas", type="string", maxLength=500, nullable=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Fichaje registrado correctamente",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="ok", type="boolean"),
 *             @OA\Property(property="tipo", type="string"),
 *             @OA\Property(property="fecha_hora", type="string", format="date-time")
 *         )
 *     ),
 *     @OA\Response(response=403, description="Cuenta bloqueada o pendiente"),
 *     @OA\Response(response=409, description="Error de método activo"),
 *     @OA\Response(response=422, description="Datos inválidos")
 * )
 *
 * @OA\Get(
 *     path="/api/fichajes/mis-fichajes",
 *     summary="Mis fichajes",
 *     description="Devuelve los fichajes y resumen diario de un usuario en una fecha específica.",
 *     tags={"Fichaje"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="fecha",
 *         in="query",
 *         description="Fecha de consulta (YYYY-MM-DD)",
 *         required=false,
 *         @OA\Schema(type="string", format="date")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Listado de fichajes y resumen",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="resumen", type="object"),
 *             @OA\Property(property="fichajes", type="array", @OA\Items(ref="#/components/schemas/Fichaje"))
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/fichajes/resumen-general",
 *     summary="Resumen general",
 *     description="Resumen de fichajes de todos los empleados de la empresa en una fecha.",
 *     tags={"Fichaje"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="fecha",
 *         in="query",
 *         description="Fecha de consulta (YYYY-MM-DD)",
 *         required=false,
 *         @OA\Schema(type="string", format="date")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Resumen general",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="fecha", type="string", format="date"),
 *             @OA\Property(property="empleados", type="array", @OA\Items(ref="#/components/schemas/Usuario")),
 *             @OA\Property(property="resumenes", type="object")
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/fichajes/estado-empresa",
 *     summary="Estado de la empresa",
 *     description="Devuelve el estado actual de fichaje de todos los empleados de la empresa y el total de minutos trabajados en un año.",
 *     tags={"Fichaje"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="empresa_id",
 *         in="query",
 *         description="ID de la empresa (solo admin_sistema)",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="anio",
 *         in="query",
 *         description="Año para cálculo de minutos trabajados",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Estado de la empresa",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="empresa_id", type="integer"),
 *             @OA\Property(property="empleados", type="array", @OA\Items(ref="#/components/schemas/Usuario"))
 *         )
 *     )
 * )
 *
 * ################ MODELO ################
 *
 * @OA\Schema(
 *     schema="Fichaje",
 *     type="object",
 *     title="Fichaje",
 *     description="Registro de fichaje",
 *     @OA\Property(property="id_fichaje", type="integer"),
 *     @OA\Property(property="tipo", type="string", enum={"entrada","salida","pausa","reanudar"}),
 *     @OA\Property(property="fecha_hora", type="string", format="date-time"),
 *     @OA\Property(property="lat", type="number", format="float"),
 *     @OA\Property(property="lng", type="number", format="float"),
 *     @OA\Property(property="notas", type="string", nullable=true),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="metodo_id", type="integer", nullable=true),
 *     @OA\Property(property="fecha_creacion", type="string", format="date-time"),
 *     @OA\Property(property="fecha_actualizacion", type="string", format="date-time")
 * )
 */
class FichajeDocs {}
