<?php

namespace Itpi\Http\Requests;

use Itpi\Core\Requests\BaseFormRequest;

class GetPRDetailRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "nomor_pr" => "required",
        ];
    }

    /**
     * Determine message when validation failed.
     *
     * @var string
     */
    public string $errorMessage = 'Payload request tidak sesuai !';
}
