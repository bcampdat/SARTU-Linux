<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="SARTU API",
 *     version="1.0.0",
 *     description="Documentación OpenAPI mínima"
 * )
 *
 * @OA\Server(
 *     url="/",
 *     description="Local server"
 * )
 *
 * @OA\PathItem(
 *     path="/api/health",
 *     @OA\Get(
 *         summary="Comprobación de estado",
 *         @OA\Response(
 *             response=200,
 *             description="OK"
 *         )
 *     )
 * )
 */
class OpenApiDocs {}
