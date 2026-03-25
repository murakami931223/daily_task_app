<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|max:20|unique:users,name'
        ];
    }

    public function messages(): array
    {
        return[
            'name.required' => 'ユーザーネームの入力は必須です。',
            'name.max' => '20文字以内で入力してください。',
            'name.unique' => 'その名前はすでに使われています。',
        ];
    }
}
