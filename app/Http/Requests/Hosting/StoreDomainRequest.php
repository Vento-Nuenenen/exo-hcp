<?php

namespace App\Http\Requests\Hosting;

use App\Traits\ValidatesHostingFeatures;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDomainRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'managed_server_id' => ['required', 'integer', 'exists:managed_servers,id'],
            'name' => ['required', 'string', 'max:255', 'unique:domains,name'],
            'web_server' => ['required', Rule::in(['nginx'])],
            ...$this->hostingFeatureRules(),
        ];
    }
}
