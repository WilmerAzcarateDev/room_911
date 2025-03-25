<?php

namespace App\Http\Requests\api;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = User::where('id',Auth::id())->first();
        if(!$user->is_admin) return false;
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
            'document'=>['required'],
            'name'=>['required','string'],
            'last_name'=>['required','string'],
            'email'=>['required','email'],
            'production_departament_id'=>['required','exists:production_departaments,id'],
            'make_admin' => ['nullable', 'boolean']
        ];
    }
}
