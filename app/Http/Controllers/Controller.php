<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="WALAB API Documentation",
 *      description="Documentation technique complète pour l'application Walab. Utilisez le bouton 'Authorize' pour tester les routes protégées.",
 *      @OA\Contact(
 *          email="contact@walab.bj"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Serveur API Principal"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Entrez votre token d'accès ici (sans le préfixe 'Bearer')"
 * )
 */
abstract class Controller
{
//
}
