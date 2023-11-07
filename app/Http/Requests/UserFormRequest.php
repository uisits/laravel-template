<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('admin');
    }

    /**
     * Add password field
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'password' => 'shibboleth',
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'name' => 'required',
            'email' => 'required',
            'netid' => 'required',
            'uin' => 'required',
            'roles' => 'sometimes',
        ];
    }
}
