<?php

namespace Itpi\Http\Requests;

use Itpi\Core\Requests\BaseFormRequest;

class GetPengadaanDetailRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'tipe_pengadaan' => 'required|string',
            'paket_lelang_id' => 'required'
        ];
    }

    /**
     * Determine message when validation failed.
     *
     * @var string
     */
    public string $errorMessage = 'Payload request tidak sesuai !';
}
