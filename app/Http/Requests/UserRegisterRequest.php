<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

class UserRegisterRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email:rfc,dns', 'unique:users,email'],
            'password' => ['required', 'string', 'max:100'],
            'role_id' => ['required', 'integer', 'exists:role,id'],
            'manager_id' => [
                'required', 
                'integer',   
                Rule::exists('users', 'id')->where(function (Builder $query) {
                    $query->whereIn('role_id', function ($sub) {
                        $sub->select('id')
                            ->from('role')
                            ->where('name', 'Manager');
                    });
                }),
            ]
        ];
    }
}
