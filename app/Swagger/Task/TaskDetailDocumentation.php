<?php

namespace App\Swagger\Task;

/**
 * @OA\Get(
 *     path="/api/tasks/{id}",
 *     summary="Get Task Detail",
 *     description="Task detail endpoint",
 *     tags={"Task"},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Task id",
 *         @OA\Schema(type="integer", example=25)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Task detail retrieving success",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=25),
 *             @OA\Property(property="title", type="string", example="Cuci AC ruang kelas"),
 *             @OA\Property(property="description", type="string", example="Cuci seluruh AC ruang kelas, dari lantai 1 sampai 10."),
 *             @OA\Property(property="status_id", type="integer", example=2),
 *             @OA\Property(
 *                 property="status",
 *                 type="object",
 *                 @OA\Property(property="name", type="string", example="Doing")
 *             ),
 *             @OA\Property(property="creator_id", type="integer", example=24),
 *             @OA\Property(
 *                 property="creator",
 *                 type="object",
 *                 @OA\Property(property="name", type="string", example="Manager")
 *             ),
 *             @OA\Property(property="assignee_id", type="integer", example=24),
 *             @OA\Property(
 *                 property="assignee",
 *                 type="object",
 *                 @OA\Property(property="name", type="string", example="Manager")
 *             ),
 *             @OA\Property(property="report", type="string", example="Seluruh AC ruang kelas asdis mengisi freon.")
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
 *     )
 * )
 */
class TaskDetailDocumentation {}
