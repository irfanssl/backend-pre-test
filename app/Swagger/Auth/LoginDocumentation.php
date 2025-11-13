<?php

namespace App\Swagger\Auth;

/**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="User Login",
     *     description="Login and get token",
     *     tags={"Authentication"},
     * 
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="manager@email.com"),
     *             @OA\Property(property="password", type="string", example="rahasia")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="qwertyuiopasdfgh")
     *         )
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Invalid credential",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Email or password is not regiestered in the system."),
     *               @OA\Property(
     *                   property="errors",
     *                   type="object",
     *                   @OA\Property(
     *                       property="email",
     *                       type="array",
     *                       @OA\Items(type="string", example="Email or password is not regiestered in the system.")
     *                   )
     *              )
     *           )
     *      )
     * )
     */

class LoginDocumentation {}