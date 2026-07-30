<?php

namespace App\Http\Requests\Hosting;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubdomainRequest extends FormRequest
{
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
            'document_root' => ['required', 'string', 'max:255'],
            'runtime' => ['required', Rule::in(['php', 'node', 'ruby'])],
            'runtime_version' => ['nullable', 'string', 'max:32'],
            'git_repository' => ['nullable', 'string', 'max:255'],
            'git_branch' => ['nullable', 'string', 'max:255'],
        ];
    }
}
