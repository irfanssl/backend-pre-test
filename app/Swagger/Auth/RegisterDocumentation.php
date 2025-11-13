<?php

namespace App\Swagger\Auth;


/**
 * @OA\Post(
 *     path="/api/register",
 *     summary="Register user",
 *     description="Register new user.",
 *     tags={"Authentication"},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","password","role_id","manager_id"},
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 maxLength=100,
 *                 example="Manager",
 *                 description="User name (100 characters max)"
 *             ),
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 format="email",
 *                 example="manager@email.com",
 *                 description="User email"
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 maxLength=100,
 *                 example="rahasia",
 *                 description="User Password (100 characters max)"
 *             ),
 *             @OA\Property(
 *                 property="role_id",
 *                 type="integer",
 *                 example=2,
 *                 description="User role id"
 *             ),
 *             @OA\Property(
 *                 property="manager_id",
 *                 type="integer",
 *                 example=5,
 *                 description="User manager id"
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Register success",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="message", type="string", example="Register Success"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=18),
 *                 @OA\Property(property="name", type="string", example="Manager"),
 *                 @OA\Property(property="email", type="string", example="manager@email.com"),
 *                 @OA\Property(property="role_id", type="integer", example=1),
 *                 @OA\Property(property="manager_id", type="integer", example=3),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-11-13T01:35:43.000000Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-11-13T01:35:43.000000Z")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation fail",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The given data was invalid."),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(
 *                     property="name",
 *                     type="array",
 *                     @OA\Items(type="string", example="The name field is required.")
 *                 ),
 *                 @OA\Property(
 *                     property="email",
 *                     type="array",
 *                     @OA\Items(type="string", example="The email has already been taken.")
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="array",
 *                     @OA\Items(type="string", example="The password field is required.")
 *                 ),
 *                 @OA\Property(
 *                     property="role_id",
 *                     type="array",
 *                     @OA\Items(type="string", example="The selected role id is invalid.")
 *                 ),
 *                 @OA\Property(
 *                     property="manager_id",
 *                     type="array",
 *                     @OA\Items(type="string", example="The selected manager id is invalid or not a Manager.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */

class RegisterDocumentation {}