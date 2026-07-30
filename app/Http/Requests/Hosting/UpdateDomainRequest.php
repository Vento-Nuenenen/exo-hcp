<?php

namespace App\Http\Requests\Hosting;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDomainRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'managed_server_id' => ['required', 'integer', 'exists:managed_servers,id'],
            'name' => ['required', 'string', 'max:255', Rule::unique('domains', 'name')->ignore($this->route('domain'))],
            'document_root' => ['required', 'string', 'max:255'],
            'web_server' => ['required', Rule::in(['nginx'])],
            'runtime' => ['required', Rule::in(['php', 'node', 'ruby'])],
            'runtime_version' => ['nullable', 'string', 'max:32'],
            'git_repository' => ['nullable', 'string', 'max:255'],
            'git_branch' => ['nullable', 'string', 'max:255'],
        ];
    }
}
