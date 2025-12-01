<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 *
 * @OA\Get(
 *     path="/api/empresas",
 *     summary="Listar empresas",
 *     description="Devuelve un listado de todas las empresas",
 *     tags={"Empresa"},
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Listado de empresas",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Empresa")
 *         )
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/empresas",
 *     summary="Crear empresa",
 *     description="Crea una nueva empresa con los datos proporcionados",
 *     tags={"Empresa"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="nombre", type="string"),
 *             @OA\Property(property="limite_usuarios", type="integer"),
 *             @OA\Property(property="jornada_diaria_minutos", type="integer"),
 *             @OA\Property(property="max_pausa_no_contabilizada", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Empresa creada",
 *         @OA\JsonContent(ref="#/components/schemas/Empresa")
 *     ),
 *     @OA\Response(response=403, description="No autorizado")
 * )
 *
 * @OA\Put(
 *     path="/api/empresas/{id}",
 *     summary="Actualizar empresa",
 *     description="Actualiza los datos de una empresa existente",
 *     tags={"Empresa"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID de la empresa a actualizar",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="nombre", type="string"),
 *             @OA\Property(property="limite_usuarios", type="integer"),
 *             @OA\Property(property="jornada_diaria_minutos", type="integer"),
 *             @OA\Property(property="max_pausa_no_contabilizada", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Empresa actualizada",
 *         @OA\JsonContent(ref="#/components/schemas/Empresa")
 *     ),
 *     @OA\Response(response=403, description="No autorizado")
 * )
 *
 * @OA\Delete(
 *     path="/api/empresas/{id}",
 *     summary="Eliminar empresa",
 *     description="Elimina una empresa si no tiene usuarios asociados",
 *     tags={"Empresa"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID de la empresa a eliminar",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Empresa eliminada"),
 *     @OA\Response(response=403, description="No autorizado"),
 *     @OA\Response(response=400, description="No se puede eliminar empresa con usuarios asociados")
 * )
 *
 * ################ MODELO ################
 *
 * @OA\Schema(
 *     schema="Empresa",
 *     type="object",
 *     title="Empresa",
 *     description="Empresa registrada en el sistema",
 *     @OA\Property(property="id_empresa", type="integer"),
 *     @OA\Property(property="nombre", type="string", maxLength=150),
 *     @OA\Property(property="limite_usuarios", type="integer"),
 *     @OA\Property(property="jornada_diaria_minutos", type="integer"),
 *     @OA\Property(property="max_pausa_no_contabilizada", type="integer"),
 *     @OA\Property(property="logo_path", type="string"),
 *     @OA\Property(property="logo_thumb", type="string"),
 *     @OA\Property(property="fecha_creacion", type="string", format="date-time"),
 *     @OA\Property(property="fecha_actualizacion", type="string", format="date-time")
 * )
 */
class EmpresaDocs {}
