<?php

namespace App\Swagger\Task;

/**
 * @OA\Patch(
 *     path="/api/tasks/{id}/status",
 *     summary="Update Task Status",
 *     description="Update task status.",
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
 *         description="Status id",
 *         @OA\JsonContent(
 *             required={"status_id"},
 *             @OA\Property(
 *                 property="status_id",
 *                 type="integer",
 *                 example=2,
 *                 description="Status id"
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Success update task status",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="message", type="string", example="Success update task status"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=26),
 *                 @OA\Property(property="title", type="string", example="Cuci AC ruang kelas ssss"),
 *                 @OA\Property(property="description", type="string", example="Cuci seluruh AC rai 10."),
 *                 @OA\Property(property="status_id", type="integer", example=2),
 *                 @OA\Property(property="creator_id", type="integer", example=3),
 *                 @OA\Property(property="assignee_id", type="integer", example=24),
 *                 @OA\Property(property="report", type="string", nullable=true, example=null),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-11-13T01:01:52.000000Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-11-13T02:40:38.000000Z")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation fail",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Status dari 'Doing' tidak dapat diubah menjadi 'Doing'."),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(
 *                     property="status_id",
 *                     type="array",
 *                     @OA\Items(type="string", example="Status dari 'Doing' tidak dapat diubah menjadi 'Doing'.")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
class TaskStatusUpdateDocumentation {}
