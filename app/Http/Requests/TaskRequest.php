<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string', 'max:2000'],
            'assignee_id' => ['nullable', 'integer']
        ];

        $user = $this->user();
        $assigneeId = $this->input('assignee_id');
        if ($user->role->name === 'Manager') {
            if($assigneeId){
                $isMyStaff = $user->where('id', $assigneeId)
                                ->where('manager_id', $user->id)
                                ->whereHas('role', function ($query) {
                                    $query->where('name', 'Staff');
                                })
                                ->exists();
                // pastikan assignee_id adalah staff milik manager tersebut
                if(!$isMyStaff){
                    $rules['assignee_id'][] = Rule::exists('users', 'id')->where(function ($query) use ($user) {
                        $query->where('manager_id', $user->id)
                            ->whereHas('role', function ($subQuery) {
                                $subQuery->where('name', 'Staff');
                            });
                    });
                }
            }
        }

        if ($user->role->name === 'Staff') {
            if($assigneeId){
                if($user->id != $assigneeId){
                    $rules['assignee_id'][] = ['prohibited'];
                }
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'assignee_id.exists' => 'assignee_id harus merupakan staff dari manager yang sedang login.',
            'assignee_id.prohibited' => 'staff hanya boleh assign ke dirinya sendiri.'
        ];
    }
}
