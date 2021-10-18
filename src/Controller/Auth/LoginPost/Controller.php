<?php

declare(strict_types=1);

namespace App\Controller\Auth\LoginPost;

use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/login", name="post_login", methods={"POST"})
 *
 * @SWG\Post(
 *     summary="Login and get JWT token",
 *     tags={"Auth"},
 *     @SWG\Parameter(
 *         name="body",
 *         in="body",
 *         type="object",
 *         required=true,
 *         @SWG\Schema(
 *             type="object",
 *             @SWG\Property(
 *                 property="username",
 *                 type="string",
 *             ),
 *             @SWG\Property(
 *                 property="password",
 *                 type="string",
 *             ),
 *         ),
 *     ),
 *     @SWG\Response(
 *         response="200",
 *         description="Returns token and refresh_token",
 *     ),
 *     @SWG\Response(
 *         response="400",
 *         description="Bad request",
 *     ),
 *     @SWG\Response(
 *         response="401",
 *         description="Unauthorized",
 *     ),
 * )
 */
final class Controller extends AbstractController
{
    public function __invoke(): void
    {
        // implemented using `json_login`, see https://symfony.com/doc/current/security/json_login_setup.html
        // intentionally left this empty method for Nelmio REST API doc
    }
}
