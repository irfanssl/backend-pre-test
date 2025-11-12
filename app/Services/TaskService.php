<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;

class TaskService
{
    /**
     * Membuat task baru sesuai aturan role dan status default.
     */
    public function createTask(array $data, User $creator): Task
    {
        return DB::transaction(function () use ($data, $creator) {
            // Default status: To Do
            $status = Status::where('name', 'To Do')->firstOrFail();

            // Tentukan siapa assignee
            if ($creator->role->name === 'Manager') {
                // Manager boleh assign ke dirinya atau staff-nya
                if (isset($data['assignee_id'])) {
                    $assignee = User::findOrFail($data['assignee_id']);
                    if ($assignee->manager_id !== $creator->id && $assignee->id !== $creator->id) {
                        throw ValidationException::withMessages([
                            'assignee_id' => 'Manager hanya dapat menetapkan tugas untuk dirinya sendiri atau staff-nya.',
                        ]);
                    }
                } else {
                    $assignee = $creator;
                }
            } else {
                // Staff hanya dapat membuat tugas untuk dirinya sendiri
                if (isset($data['assignee_id']) && $data['assignee_id'] != $creator->id) {
                    throw ValidationException::withMessages([
                        'assignee_id' => 'Staff hanya dapat membuat tugas untuk dirinya sendiri.',
                    ]);
                }
                $assignee = $creator;
            }

            return Task::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'status_id' => $status->id,
                'creator_id' => $creator->id,
                'assignee_id' => $assignee->id,
                'report' => null,
            ]);
        });
    }

    /**
     * Mengupdate data task (hanya creator, kecuali status Doing/Done).
     */
    public function updateTask(Task $task, array $data, User $user): Task
    {
        if ($task->creator_id !== $user->id) {
            throw ValidationException::withMessages([
                'permission' => 'Hanya pembuat tugas yang dapat mengubah tugas.',
            ]);
        }

        $statusName = $task->status->name;
        if (in_array($statusName, ['Doing', 'Done'])) {
            throw ValidationException::withMessages([
                'status' => 'Tugas tidak dapat diubah jika statusnya Doing atau Done.',
            ]);
        }

        return DB::transaction(function () use ($task, $data, $user) {
            $task->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'assignee_id' => $data['assignee_id'] ?? $task->assignee_id,
            ]);

            return $task->fresh();
        });
    }

    /**
     * Mengubah status task dengan aturan transisi yang ketat.
     */
    public function changeStatus(Task $task, int $statusId, User $user): Task
    {
        $newStatus = Status::findOrFail($statusId);
        $currentStatus = $task->status->name;
        $targetStatus = $newStatus->name;

        // Hanya pembuat atau pelaksana yang boleh ubah status
        if ($user->id !== $task->creator_id && $user->id !== $task->assignee_id) {
            throw ValidationException::withMessages([
                'permission' => 'Hanya pembuat atau pelaksana yang dapat mengubah status tugas.',
            ]);
        }

        // Validasi transisi
        $this->validateStatusTransition($currentStatus, $targetStatus, $task, $user);

        return DB::transaction(function () use ($task, $newStatus) {
            $task->update(['status_id' => $newStatus->id]);
            return $task->fresh();
        });
    }

    /**
     * Mengisi atau memperbarui laporan (report).
     */
    public function updateReport(Task $task, string $report, User $user): Task
    {
        if ($task->status->name !== 'Doing') {
            throw ValidationException::withMessages([
                'status' => 'Report hanya dapat diisi saat status Doing.',
            ]);
        }

        if ($user->id !== $task->creator_id && $user->id !== $task->assignee_id) {
            throw ValidationException::withMessages([
                'permission' => 'Hanya pembuat atau pelaksana yang dapat mengisi report.',
            ]);
        }

        return DB::transaction(function () use ($task, $report) {
            $task->update(['report' => $report]);
            return $task->fresh();
        });
    }

    /**
     * Mengambil task beserta relasi.
     */
    public function getTask(Task $task): Task
    {
        return $task->load(['status', 'creator', 'assignee']);
    }

    /**
     * Validasi aturan transisi antar status.
     */
    protected function validateStatusTransition(string $current, string $target, Task $task, User $user): void
    {
        $creator = $task->creator;
        $isCreator = $user->id === $creator->id;

        $allowed = [
            'To Do' => ['Doing', 'Canceled'],
            'Doing' => ['Done', 'To Do'],
            'Done' => [],
            'Canceled' => ['To Do'],
        ];

        if (!isset($allowed[$current]) || !in_array($target, $allowed[$current])) {
            throw ValidationException::withMessages([
                'status_id' => "Status dari '{$current}' tidak dapat diubah menjadi '{$target}'.",
            ]);
        }

        // Jika ingin membatalkan (Canceled)
        if ($target === 'Canceled') {
            if (!$isCreator) {
                throw ValidationException::withMessages([
                    'permission' => 'Hanya pembuat tugas yang dapat membatalkan tugas.',
                ]);
            }
            if (!empty($task->report)) {
                throw ValidationException::withMessages([
                    'report' => 'Tugas tidak dapat dibatalkan karena report sudah diisi.',
                ]);
            }
        }

        // Jika ingin mengubah ke 'To Do' kembali
        if ($target === 'To Do' && !empty($task->report)) {
            throw ValidationException::withMessages([
                'report' => 'Tugas tidak dapat dikembalikan ke To Do jika report sudah diisi.',
            ]);
        }
    }
}
