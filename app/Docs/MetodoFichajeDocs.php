<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * ################ ENDPOINTS METODO FICHAJE ################
 *
 * @OA\Get(
 *     path="/api/metodos-fichaje",
 *     summary="Listar métodos de fichaje",
 *     description="Devuelve todos los métodos de fichaje registrados en el sistema.",
 *     tags={"MetodoFichaje"},
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Listado de métodos de fichaje",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/MetodoFichaje")
 *         )
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/metodos-fichaje",
 *     summary="Crear método de fichaje",
 *     description="Crea un nuevo método de fichaje.",
 *     tags={"MetodoFichaje"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="nombre", type="string"),
 *             @OA\Property(property="slug", type="string"),
 *             @OA\Property(property="descripcion", type="string", nullable=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Método de fichaje creado",
 *         @OA\JsonContent(ref="#/components/schemas/MetodoFichaje")
 *     ),
 *     @OA\Response(response=422, description="Validación fallida")
 * )
 *
 * @OA\Get(
 *     path="/api/metodos-fichaje/{id}",
 *     summary="Ver método de fichaje",
 *     description="Muestra los datos de un método de fichaje específico.",
 *     tags={"MetodoFichaje"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID del método de fichaje",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Datos del método de fichaje",
 *         @OA\JsonContent(ref="#/components/schemas/MetodoFichaje")
 *     ),
 *     @OA\Response(response=404, description="Método no encontrado")
 * )
 *
 * @OA\Delete(
 *     path="/api/metodos-fichaje/{id}",
 *     summary="Eliminar método de fichaje",
 *     description="Elimina un método de fichaje si no está en uso.",
 *     tags={"MetodoFichaje"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID del método de fichaje a eliminar",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Método eliminado"),
 *     @OA\Response(response=409, description="No se puede eliminar (en uso)")
 * )
 *
 * ################ MODELO ################
 *
 * @OA\Schema(
 *     schema="MetodoFichaje",
 *     type="object",
 *     title="MetodoFichaje",
 *     description="Método de fichaje",
 *     @OA\Property(property="id_metodo", type="integer"),
 *     @OA\Property(property="nombre", type="string", maxLength=100),
 *     @OA\Property(property="slug", type="string", maxLength=50),
 *     @OA\Property(property="descripcion", type="string", nullable=true),
 *     @OA\Property(property="fecha_creacion", type="string", format="date-time"),
 *     @OA\Property(property="fecha_modificacion", type="string", format="date-time")
 * )
 */
class MetodoFichajeDocs {}
