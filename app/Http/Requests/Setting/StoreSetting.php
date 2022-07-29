<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class StoreSetting extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'envato_username' => 'required',
            'envato_api_key' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'envato_api_key' => 'Envato Personal Token'
        ];
    }

}
