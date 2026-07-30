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
    runtime: string;
    runtime_version?: string;
    document_root: string;
    web_server: string;
    git_repository?: string;
    git_branch?: string;
    user_id: number;
    managed_server_id: number;
}

interface UserItem {
    id: number;
    name: string;
}

interface ManagedServerItem {
    id: number;
    name: string;
}

const props = defineProps<{
    domain: DomainItem;
    users: UserItem[];
    managedServers: ManagedServerItem[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Hosting', href: '/hosting' },
            { title: 'Edit Domain' },
        ],
    },
});
</script>

<template>
    <Head :title="`Edit Domain: ${domain.name}`" />

    <div class="mx-auto flex w-full max-w-2xl flex-col gap-8 p-6">
        <div class="flex items-center justify-between">
            <Link href="/hosting" class="flex items-center text-sm text-muted-foreground hover:text-foreground">
                <ArrowLeft class="mr-2 h-4 w-4" />
                Back to Dashboard
            </Link>
        </div>

        <Heading
            :title="`Edit Domain: ${domain.name}`"
            description="Update domain configuration, runtime settings, and Git integration."
        />

        <Card>
            <CardHeader>
                <CardTitle>Domain Configuration</CardTitle>
                <CardDescription>Modify the settings for your primary domain.</CardDescription>
            </CardHeader>
            <CardContent>
                <Form :action="`/hosting/domains/${domain.id}`" method="patch" class="space-y-6">
                    <div class="space-y-2">
                        <Label>Domain Name</Label>
                        <Input name="name" :value="domain.name" required />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label>User</Label>
                            <select
                                name="user_id"
                                class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:outline-none"
                                required
                            >
                                <option
                                    v-for="user in users"
                                    :key="user.id"
                                    :value="user.id"
                                    :selected="user.id === domain.user_id"
                                >
                                    {{ user.name }}
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
                                    :selected="server.id === domain.managed_server_id"
                                >
                                    {{ server.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label>Document Root</Label>
                        <Input name="document_root" :value="domain.document_root" required />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label>Runtime</Label>
                            <select
                                name="runtime"
                                class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:outline-none"
                                required
                            >
                                <option value="php" :selected="domain.runtime === 'php'">PHP</option>
                                <option value="node" :selected="domain.runtime === 'node'">Node.js</option>
                                <option value="ruby" :selected="domain.runtime === 'ruby'">Ruby</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <Label>Version</Label>
                            <Input name="runtime_version" :value="domain.runtime_version" placeholder="8.3" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label>Git Repository (Optional)</Label>
                        <Input
                            name="git_repository"
                            :value="domain.git_repository"
                            placeholder="https://github.com/user/repo"
                        />
                    </div>
                    <div class="space-y-2">
                        <Label>Git Branch</Label>
                        <Input name="git_branch" :value="domain.git_branch || 'main'" />
                    </div>

                    <input type="hidden" name="web_server" :value="domain.web_server" />

                    <Button type="submit" class="w-full">
                        <Save class="mr-2 h-4 w-4" /> Save Changes
                    </Button>
                </Form>
            </CardContent>
        </Card>
    </div>
</template>
