<?php

namespace Itpi\Http\Requests;

use Itpi\Core\Requests\BaseFormRequest;

class GetContractDocsRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "id" => "required|int",
            "page" => "required|int",
            "limit" => "required|int",
        ];
    }

    /**
     * Determine message when validation failed.
     *
     * @var string
     */
    public string $errorMessage = 'Payload request tidak sesuai !';
}
