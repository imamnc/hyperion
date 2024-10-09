<?php

namespace Itpi\Http\Requests;

use Itpi\Core\Requests\BaseFormRequest;

class LoginRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'project' => 'required|string|exists:projects,code',
            'email' => 'required|string',
            'password' => 'required|string',
        ];
    }

    /**
     * Determine message when validation failed.
     *
     * @var string
     */
    public string $errorMessage = 'Payload request tidak sesuai !';
}
