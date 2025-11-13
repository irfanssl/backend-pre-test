<?php

namespace App\Swagger\Task;

/**
 * @OA\Patch(
 *     path="/api/tasks/{id}/report",
 *     summary="Update Task Report",
 *     description="Update task report endpoint",
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
 *         description="report (5000 characters max)",
 *         @OA\JsonContent(
 *             required={"report"},
 *             @OA\Property(
 *                 property="report",
 *                 type="string",
 *                 maxLength=5000,
 *                 example="Seluruh AC ruang kelas asdis mengisi freon.",
 *                 description="Isi laporan pekerjaan, maksimal 5000 karakter"
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Success update task report",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="Success"),
 *             @OA\Property(property="message", type="string", example="Success update task report"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=26),
 *                 @OA\Property(property="title", type="string", example="Cuci AC ruang kelas ssss"),
 *                 @OA\Property(property="description", type="string", example="Cuci seluruh AC rai 10."),
 *                 @OA\Property(property="status_id", type="integer", example=2),
 *                 @OA\Property(property="creator_id", type="integer", example=3),
 *                 @OA\Property(property="assignee_id", type="integer", example=24),
 *                 @OA\Property(property="report", type="string", example="Seluruh AC ruang kelas asdis mengisi freon."),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-11-13T01:01:52.000000Z"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-11-13T03:03:52.000000Z")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Validation fail",
 *         @OA\JsonContent(
 *             oneOf={
 *                 @OA\Schema(  
 *                     @OA\Property(property="message", type="string", example="Report hanya dapat diisi saat status Doing."),
 *                     @OA\Property(
 *                         property="errors",
 *                         type="object",
 *                         @OA\Property(
 *                             property="status",
 *                             type="array",
 *                             @OA\Items(type="string", example="Report hanya dapat diisi saat status Doing.")
 *                         )
 *                     )
 *                 ),
 *                 @OA\Schema(  
 *                     @OA\Property(property="message", type="string", example="Hanya pembuat atau pelaksana yang dapat mengisi report."),
 *                     @OA\Property(
 *                         property="errors",
 *                         type="object",
 *                         @OA\Property(
 *                             property="permission",
 *                             type="array",
 *                             @OA\Items(type="string", example="Hanya pembuat atau pelaksana yang dapat mengisi report.")
 *                         )
 *                     )
 *                 )
 *             }
 *         )
 *     )
 * )
 */
class TaskReportUpdateDocumentation {}
