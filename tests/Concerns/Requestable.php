<?php

declare(strict_types=1);

namespace Tests\Concerns;

use Illuminate\Support\Facades\Validator;
use Tests\Concerns\Http\FormRequest;

trait Requestable
{
    protected function formRequest(array $data): FormRequest
    {
        $request = FormRequest::create($this->baseUrl, parameters: $data);

        $validator = Validator::make($data, $request->rules());

        $request->setValidator($validator);

        return $request;
    }
}
