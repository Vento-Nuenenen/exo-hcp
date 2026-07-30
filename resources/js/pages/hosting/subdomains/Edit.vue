<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { Save, ArrowLeft } from '@lucide/vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface DomainItem {
    id: number;
    name: string;
}

interface SubdomainItem {
    id: number;
    name: string;
    runtime: string;
    runtime_version?: string;
    document_root: string;
    git_repository?: string;
    git_branch?: string;
    domain_id: number;
    managed_server_id: number;
}

interface ManagedServerItem {
    id: number;
    name: string;
}

const props = defineProps<{
    subdomain: SubdomainItem;
    domains: DomainItem[];
    managedServers: ManagedServerItem[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Hosting', href: '/hosting' },
            { title: 'Edit Subdomain' },
        ],
    },
});
</script>

<template>
    <Head :title="`Edit Subdomain: ${subdomain.name}`" />

    <div class="mx-auto flex w-full max-w-2xl flex-col gap-8 p-6">
        <div class="flex items-center justify-between">
            <Link href="/hosting" class="flex items-center text-sm text-muted-foreground hover:text-foreground">
                <ArrowLeft class="mr-2 h-4 w-4" />
                Back to Dashboard
            </Link>
        </div>

        <Heading
            :title="`Edit Subdomain: ${subdomain.name}`"
            description="Update subdomain configuration, runtime settings, and Git integration."
        />

        <Card>
            <CardHeader>
                <CardTitle>Subdomain Configuration</CardTitle>
                <CardDescription>Modify the settings for your subdomain.</CardDescription>
            </CardHeader>
            <CardContent>
                <Form :action="`/hosting/subdomains/${subdomain.id}`" method="patch" class="space-y-6">
                    <div class="space-y-2">
                        <Label>Subdomain Label</Label>
                        <div class="flex items-center gap-2">
                            <Input name="name" :value="subdomain.name" required class="flex-1" />
                            <span class="text-sm text-muted-foreground">.{{ domains.find(d => d.id === subdomain.domain_id)?.name }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label>Parent Domain</Label>
                            <select
                                name="domain_id"
                                class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:outline-none"
                                required
                            >
                                <option
                                    v-for="domain in domains"
                                    :key="domain.id"
                                    :value="domain.id"
                                    :selected="domain.id === subdomain.domain_id"
                                >
                                    {{ domain.name }}
                                </option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <Label>Server</Label>
                            <select
                                name="managed_server_id"
                                class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:outline-none"
                                required
                            >
                                <option
                                    v-for="server in managedServers"
                                    :key="server.id"
                                    :value="server.id"
                                    :selected="server.id === subdomain.managed_server_id"
                                >
                                    {{ server.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label>Document Root</Label>
                        <Input name="document_root" :value="subdomain.document_root" required />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label>Runtime</Label>
                            <select
                                name="runtime"
                                class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:outline-none"
                                required
                            >
                                <option value="php" :selected="subdomain.runtime === 'php'">PHP</option>
                                <option value="node" :selected="subdomain.runtime === 'node'">Node.js</option>
                                <option value="ruby" :selected="subdomain.runtime === 'ruby'">Ruby</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <Label>Version</Label>
                            <Input name="runtime_version" :value="subdomain.runtime_version" placeholder="20" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label>Git Repository (Optional)</Label>
                        <Input
                            name="git_repository"
                            :value="subdomain.git_repository"
                            placeholder="https://github.com/user/repo"
                        />
                    </div>
                    <div class="space-y-2">
                        <Label>Git Branch</Label>
                        <Input name="git_branch" :value="subdomain.git_branch || 'main'" />
                    </div>

                    <Button type="submit" class="w-full">
                        <Save class="mr-2 h-4 w-4" /> Save Changes
                    </Button>
                </Form>
            </CardContent>
        </Card>
    </div>
</template>
