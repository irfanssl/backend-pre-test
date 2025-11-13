<?php

namespace App\Swagger\Task;

/**
 * @OA\Post(
 *     path="/api/tasks",
 *     summary="Create task",
 *     description="Create task endpoint",
 *     tags={"Task"},
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title","description"},
 *             @OA\Property(
 *                 property="title",
 *                 type="string",
 *                 maxLength=50,
 *                 example="Cuci AC ruang kelas",
 *                 description="Task title, (50 characters max)"
 *             ),
 *             @OA\Property(
 *                 property="description",
 *                 type="string",
 *                 maxLength=2000,
 *                 example="Cuci seluruh AC ruang kelas, dari lantai 1 sampai 10.",
 *                 description="Task desctription, (2000 characters max)"
 *             ),
 *             @OA\Property(
 *                 property="assignee_id",
 *                 type="integer",
 *                 nullable=true,
 *                 example=24,
 *                 description="Assignee id. 
 *                 - Jika Manager: hanya boleh menugaskan Staff yang menjadi bawahannya.
 *                 - Jika Staff: hanya boleh menugaskan dirinya sendiri."
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Success create task",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="message", type="string", example="Success Create Task"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=26),
 *                 @OA\Property(property="title", type="string", example="Cuci AC ruang kelas"),
 *                 @OA\Property(property="description", type="string", example="Cuci seluruh AC ruang kelas, dari lantai 1 sampai 10."),
 *                 @OA\Property(property="status_id", type="integer", example=1),
 *                 @OA\Property(property="creator_id", type="integer", example=3),
 *                 @OA\Property(property="assignee_id", type="integer", nullable=true, example=24),
 *                 @OA\Property(property="report", type="string", nullable=true, example=null),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-11-13T01:01:52.000000Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-11-13T01:01:52.000000Z")
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
 *                     property="title",
 *                     type="array",
 *                     @OA\Items(type="string", example="The title field is required.")
 *                 ),
 *                 @OA\Property(
 *                     property="description",
 *                     type="array",
 *                     @OA\Items(type="string", example="The description field is required.")
 *                 ),
 *                 @OA\Property(
 *                     property="assignee_id",
 *                     type="array",
 *                     @OA\Items(
 *                         type="string",
 *                         example="The selected assignee id is invalid or not your staff."
 *                     )
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class TaskCreateDocumentation {}
