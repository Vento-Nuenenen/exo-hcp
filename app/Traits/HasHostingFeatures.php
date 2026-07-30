<?php

namespace App\Traits;

use App\Models\Subdomain;

trait HasHostingFeatures
{
    /**
     * Get the full URL for the domain/subdomain.
     */
    public function getFullUrl(): string
    {
        if ($this instanceof Subdomain) {
            return $this->name.'.'.$this->domain->name;
        }

        return $this->name;
    }

    /**
     * Determine if Git is configured.
     */
    public function hasGitConfigured(): bool
    {
        return ! empty($this->git_repository);
    }
}
