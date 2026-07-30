<?php

namespace App\Traits;

use Illuminate\Validation\Rule;

trait ValidatesHostingFeatures
{
    /**
     * Get the common hosting feature validation rules.
     *
     * @return array<string, mixed>
     */
    protected function hostingFeatureRules(): array
    {
        return [
            'document_root' => ['required', 'string', 'max:255'],
            'runtime' => ['required', Rule::in(['php', 'node', 'ruby'])],
            'runtime_version' => ['nullable', 'string', 'max:32'],
            'git_repository' => ['nullable', 'string', 'max:255'],
            'git_branch' => ['nullable', 'string', 'max:255'],
        ];
    }
}
