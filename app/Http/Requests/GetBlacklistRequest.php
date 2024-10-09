<?php

namespace Itpi\Http\Requests;

use Itpi\Core\Requests\BaseFormRequest;

class GetBlacklistRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'page' => 'required|int', // Untuk menentukan page
            'limit' => 'required|int', // Untuk menentukan jumlah data tiap page
            'search' => 'nullable|string', // Untuk query search, bisa dikosongi
            'flag_status' => 'required|string', // true = Blacklist, false = Unblacklist
        ];
    }

    /**
     * Determine message when validation failed.
     *
     * @var string
     */
    public string $errorMessage = 'Payload request tidak sesuai !';
}
