<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     title="Kledo Task API",
 *     version="1.0.0",
 *     description="Dokumentasi API sistem penugasan Kledo menggunakan Laravel 12 + Sanctum + L5-Swagger"
 * ),
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * ),
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class OpenApi
{
    // file ini hanya untuk anotasi swagger, tidak perlu isi apapun
}
