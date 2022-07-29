<?php

namespace App\Http\Requests\License;

use Illuminate\Foundation\Http\FormRequest;

class VerifyLicense extends FormRequest
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
            'email' => 'sometimes|required|max:255',
            'purchase_code' => 'required|min:36',
            'g-recaptcha-response' => 'sometimes|required|recaptcha'
        ];
    }

}
