<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FQDN implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('/^(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,6}$/i', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Your domain name is invalid.<br> Please add correct domain/subdomain in format (froiden.com or product.froiden.com)<br> Add domain without http or https';
    }
}
