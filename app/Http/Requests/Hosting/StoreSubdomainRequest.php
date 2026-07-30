<?php

namespace App\Http\Requests\Hosting;

use App\Traits\ValidatesHostingFeatures;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubdomainRequest extends FormRequest
{
    use ValidatesHostingFeatures;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'domain_id' => ['required', 'integer', 'exists:domains,id'],
            'managed_server_id' => ['required', 'integer', 'exists:managed_servers,id'],
            'name' => ['required', 'string', 'max:255'],
            ...$this->hostingFeatureRules(),
        ];
    }
}
