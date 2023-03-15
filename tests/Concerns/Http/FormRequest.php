<?php

declare(strict_types=1);

namespace Tests\Concerns\Http;

use Illuminate\Foundation\Http\FormRequest as BaseRequest;

class FormRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'foo' => ['required', 'string'],
            'bar' => ['required', 'string'],
        ];
    }
}
