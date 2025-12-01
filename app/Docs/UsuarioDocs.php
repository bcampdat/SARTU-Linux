<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * ################ ENDPOINTS USUARIO ################
 *
 * @OA\Get(
 *     path="/api/usuarios",
 *     summary="Listar usuarios",
 *     description="Lista usuarios según el rol del usuario autenticado (admin_sistema o encargado). Permite filtrar por email.",
 *     tags={"Usuario"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="email",
 *         in="query",
 *         description="Filtrar por email",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Listado de usuarios paginado",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Usuario")),
 *             @OA\Property(property="links", type="object"),
 *             @OA\Property(property="meta", type="object")
 *         )
 *     ),
 *     @OA\Response(response=403, description="No autorizado")
 * )
 *
 * @OA\Post(
 *     path="/api/usuarios",
 *     summary="Crear usuario",
 *     description="Crea un usuario en la empresa correspondiente según el rol del usuario autenticado.",
 *     tags={"Usuario"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="empresa_id", type="integer"),
 *             @OA\Property(property="rol_id", type="integer"),
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string", format="email")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Usuario creado",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="usuario", ref="#/components/schemas/Usuario"),
 *             @OA\Property(property="password_temporal", type="string")
 *         )
 *     ),
 *     @OA\Response(response=403, description="No autorizado")
 * )
 *
 * @OA\Get(
 *     path="/api/usuarios/{id}",
 *     summary="Ver usuario",
 *     description="Muestra los datos de un usuario específico, incluyendo empresa y rol.",
 *     tags={"Usuario"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID del usuario",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Datos del usuario",
 *         @OA\JsonContent(ref="#/components/schemas/Usuario")
 *     ),
 *     @OA\Response(response=403, description="No autorizado")
 * )
 *
 * @OA\Put(
 *     path="/api/usuarios/{id}",
 *     summary="Actualizar usuario",
 *     description="Actualiza los datos de un usuario existente.",
 *     tags={"Usuario"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID del usuario a actualizar",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="empresa_id", type="integer"),
 *             @OA\Property(property="rol_id", type="integer"),
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="estado", type="string", enum={"pendiente","activo","bloqueado"})
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Usuario actualizado",
 *         @OA\JsonContent(ref="#/components/schemas/Usuario")
 *     ),
 *     @OA\Response(response=403, description="No autorizado"),
 *     @OA\Response(response=409, description="Usuario bloqueado")
 * )
 *
 * @OA\Delete(
 *     path="/api/usuarios/{id}",
 *     summary="Eliminar usuario",
 *     description="Elimina un usuario si el rol y empresa lo permiten.",
 *     tags={"Usuario"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID del usuario a eliminar",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Usuario eliminado"),
 *     @OA\Response(response=403, description="No autorizado")
 * )
 *
 * ################ MODELO ################
 *
 * @OA\Schema(
 *     schema="Usuario",
 *     type="object",
 *     title="Usuario",
 *     description="Usuario del sistema",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string", maxLength=120),
 *     @OA\Property(property="email", type="string", maxLength=150),
 *     @OA\Property(property="estado", type="string", enum={"pendiente","activo","bloqueado"}),
 *     @OA\Property(property="activo", type="boolean"),
 *     @OA\Property(property="empresa_id", type="integer"),
 *     @OA\Property(property="rol_id", type="integer"),
 *     @OA\Property(property="fecha_registro", type="string", format="date-time"),
 *     @OA\Property(property="fecha_actualizacion", type="string", format="date-time")
 * )
 */
class UsuarioDocs {}
