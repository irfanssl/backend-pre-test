<?php

namespace App\Swagger\Task;

/**
 * @OA\Put(
 *     path="/api/tasks/{id}",
 *     summary="Update Task",
 *     description="Endpoint updating task",
 *     tags={"Task"},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Task id",
 *         @OA\Schema(type="integer", example=26)
 *     ),
 *
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "description"},
 *             @OA\Property(
 *                 property="title",
 *                 type="string",
 *                 maxLength=50,
 *                 example="Cuci AC ruang kelas ssss",
 *                 description="Task title, (50 characters max)"
 *             ),
 *             @OA\Property(
 *                 property="description",
 *                 type="string",
 *                 maxLength=2000,
 *                 example="Cuci seluruh AC rai 10.",
 *                 description="Task description, (2000 characters max)"
 *             ),
 *             @OA\Property(
 *                 property="assignee_id",
 *                 type="integer",
 *                 nullable=true,
 *                 example=24,
 *                 description="Assignee id. 
 *                 - Manager: hanya boleh assign ke staff miliknya.
 *                 - Staff: hanya boleh assign ke dirinya sendiri."
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Success Create Task",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="message", type="string", example="Success Update Task"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=26),
 *                 @OA\Property(property="title", type="string", example="Cuci AC ruang kelas ssss"),
 *                 @OA\Property(property="description", type="string", example="Cuci seluruh AC rai 10."),
 *                 @OA\Property(property="status_id", type="integer", example=1),
 *                 @OA\Property(property="creator_id", type="integer", example=3),
 *                 @OA\Property(property="assignee_id", type="integer", nullable=true, example=24),
 *                 @OA\Property(property="report", type="string", nullable=true, example=null),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-11-13T01:01:52.000000Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-11-13T02:11:28.000000Z")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Task not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="Fail"),
 *             @OA\Property(property="message", type="string", example="Task not found"),
 *             @OA\Property(property="data", type="string", nullable=true, example=null)
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
 *                     property="assignee_id",
 *                     type="array",
 *                     @OA\Items(
 *                         type="string",
 *                         example="assignee_id harus merupakan staff dari manager yang sedang login."
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="assignee_id_prohibited",
 *                     type="array",
 *                     @OA\Items(
 *                         type="string",
 *                         example="staff hanya boleh assign ke dirinya sendiri."
 *                     )
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class TaskUpdateDocumentation {}
