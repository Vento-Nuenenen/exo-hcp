<?php

namespace App\Services;

use App\Models\Domain;
use App\Models\ManagedServer;
use App\Models\Subdomain;

class ConfigExportService
{
    /**
     * Export the configuration for a managed server in JSON format.
     */
    public function exportToJson(ManagedServer $server): string
    {
        $config = [
            'id' => $server->id,
            'name' => $server->name,
            'agent_token' => $server->agent_token,
            'domains' => $server->domains->map(fn (Domain $domain) => [
                'id' => $domain->id,
                'name' => $domain->name,
                'document_root' => $domain->document_root,
                'web_server' => $domain->web_server,
                'runtime' => $domain->runtime,
                'runtime_version' => $domain->runtime_version,
                'git' => $domain->git_repository ? [
                    'repository' => $domain->git_repository,
                    'branch' => $domain->git_branch ?? 'main',
                ] : null,
                'subdomains' => $domain->subdomains->map(fn (Subdomain $subdomain) => [
                    'id' => $subdomain->id,
                    'name' => $subdomain->name,
                    'full_domain' => $subdomain->name.'.'.$domain->name,
                    'document_root' => $subdomain->document_root,
                    'runtime' => $subdomain->runtime,
                    'runtime_version' => $subdomain->runtime_version,
                    'git' => $subdomain->git_repository ? [
                        'repository' => $subdomain->git_repository,
                        'branch' => $subdomain->git_branch ?? 'main',
                    ] : null,
                ]),
            ]),
        ];

        return json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
