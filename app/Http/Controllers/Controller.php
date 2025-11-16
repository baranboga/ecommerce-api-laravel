<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="E-Ticaret API",
 *     version="1.0.0",
 *     description="E-Ticaret API Dokümantasyonu"
 * )
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local Server"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
abstract class Controller
{
    //
}
