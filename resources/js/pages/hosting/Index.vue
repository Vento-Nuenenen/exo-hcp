<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import {
    Globe,
    Server,
    User,
    Plus,
    GitBranch,
    Cpu,
    Trash2,
    ExternalLink,
    Download,
} from '@lucide/vue';
import { ref } from 'vue';
import Heading from '@/components/Heading.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

interface UserItem {
    id: number;
    name: string;
    email: string;
}

interface ManagedServerItem {
    id: number;
    name: string;
    host: string;
    port: number;
    is_active: boolean;
}

interface DomainItem {
    id: number;
    name: string;
    runtime: string;
    runtime_version?: string;
    web_server: string;
    git_repository?: string;
    git_branch?: string;
    user: UserItem;
    managed_server: ManagedServerItem;
}

interface SubdomainItem {
    id: number;
    name: string;
    runtime: string;
    runtime_version?: string;
    git_repository?: string;
    git_branch?: string;
    domain: Pick<DomainItem, 'id' | 'name'>;
    managed_server: ManagedServerItem;
}

defineProps<{
    users: UserItem[];
    managedServers: ManagedServerItem[];
    domains: DomainItem[];
    subdomains: SubdomainItem[];
}>();

const activeTab = ref('overview');

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Hosting', href: '/hosting' }],
    },
});
</script>

<template>
    <Head title="Hosting Control Panel" />

    <div class="mx-auto flex w-full max-w-7xl flex-col gap-8 p-6">
        <div class="flex items-center justify-between">
            <Heading
                title="Hosting Control Panel"
                description="Manage your infrastructure, domains, and deployments."
            />
        </div>

        <div class="flex gap-2 border-b pb-px">
            <button
                v-for="tab in ['overview', 'domains', 'servers', 'users']"
                :key="tab"
                @click="activeTab = tab"
                :class="[
                    'relative px-4 py-2 text-sm font-medium transition-colors',
                    activeTab === tab
                        ? 'text-foreground'
                        : 'text-muted-foreground hover:text-foreground',
                ]"
            >
                {{ tab.charAt(0).toUpperCase() + tab.slice(1) }}
                <div
                    v-if="activeTab === tab"
                    class="absolute right-0 bottom-0 left-0 h-0.5 bg-primary"
                ></div>
            </button>
        </div>

        <!-- OVERVIEW TAB -->
        <div v-if="activeTab === 'overview'" class="space-y-8">
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between pb-2"
                    >
                        <CardTitle class="text-sm font-medium"
                            >Total Domains</CardTitle
                        >
                        <Globe class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ domains.length }}
                        </div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between pb-2"
                    >
                        <CardTitle class="text-sm font-medium"
                            >Subdomains</CardTitle
                        >
                        <Globe class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ subdomains.length }}
                        </div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between pb-2"
                    >
                        <CardTitle class="text-sm font-medium"
                            >Active Servers</CardTitle
                        >
                        <Server class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{
                                managedServers.filter((s) => s.is_active).length
                            }}
                        </div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between pb-2"
                    >
                        <CardTitle class="text-sm font-medium">Users</CardTitle>
                        <User class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ users.length }}</div>
                    </CardContent>
                </Card>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Recent Domains</CardTitle>
                        <CardDescription
                            >Recently added domains and their
                            status.</CardDescription
                        >
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div
                                v-for="domain in domains.slice(0, 5)"
                                :key="domain.id"
                                class="flex items-center justify-between"
                            >
                                <div class="flex items-center gap-3">
                                    <div class="rounded-full bg-primary/10 p-2">
                                        <Globe class="h-4 w-4 text-primary" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium">
                                            {{ domain.name }}
                                        </div>
                                        <div
                                            class="text-xs text-muted-foreground"
                                        >
                                            {{ domain.managed_server.name }}
                                        </div>
                                    </div>
                                </div>
                                <Badge variant="outline"
                                    >{{ domain.runtime }}
                                    {{ domain.runtime_version }}</Badge
                                >
                            </div>
                            <div
                                v-if="domains.length === 0"
                                class="py-4 text-center text-sm text-muted-foreground"
                            >
                                No domains yet.
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Quick Actions</CardTitle>
                        <CardDescription
                            >Commonly used operations.</CardDescription
                        >
                    </CardHeader>
                    <CardContent class="grid grid-cols-2 gap-4">
                        <Button
                            variant="outline"
                            class="h-auto flex-col gap-2 p-4"
                            @click="activeTab = 'domains'"
                        >
                            <Globe class="h-5 w-5" />
                            <span>Add Domain</span>
                        </Button>
                        <Button
                            variant="outline"
                            class="h-auto flex-col gap-2 p-4"
                            @click="activeTab = 'servers'"
                        >
                            <Server class="h-5 w-5" />
                            <span>Add Server</span>
                        </Button>
                    </CardContent>
                </Card>
            </div>
        </div>

        <!-- DOMAINS TAB -->
        <div v-if="activeTab === 'domains'" class="grid gap-8 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <Card v-for="domain in domains" :key="domain.id">
                    <CardHeader class="pb-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <Globe class="h-5 w-5 text-primary" />
                                <CardTitle>{{ domain.name }}</CardTitle>
                            </div>
                            <div class="flex gap-2">
                                <Button variant="ghost" size="icon" as-child>
                                    <Link
                                        :href="`/hosting/domains/${domain.id}`"
                                        method="delete"
                                        as="button"
                                    >
                                        <Trash2
                                            class="h-4 w-4 text-destructive"
                                        />
                                    </Link>
                                </Button>
                            </div>
                        </div>
                        <CardDescription
                            >Managed by {{ domain.user.name }} on
                            {{ domain.managed_server.name }}</CardDescription
                        >
                    </CardHeader>
                    <CardContent>
                        <div
                            class="grid grid-cols-2 gap-4 text-sm sm:grid-cols-4"
                        >
                            <div
                                class="flex items-center gap-2 text-muted-foreground"
                            >
                                <Cpu class="h-4 w-4" />
                                <span
                                    >{{ domain.runtime }}
                                    {{ domain.runtime_version }}</span
                                >
                            </div>
                            <div
                                class="flex items-center gap-2 text-muted-foreground"
                            >
                                <Server class="h-4 w-4" />
                                <span>{{ domain.web_server }}</span>
                            </div>
                            <div
                                v-if="domain.git_repository"
                                class="col-span-2 flex items-center gap-2 truncate text-muted-foreground"
                            >
                                <GitBranch class="h-4 w-4" />
                                <span class="truncate"
                                    >{{ domain.git_repository }} ({{
                                        domain.git_branch
                                    }})</span
                                >
                            </div>
                        </div>

                        <div
                            v-if="
                                subdomains.filter(
                                    (s) => s.domain.id === domain.id,
                                ).length > 0
                            "
                            class="mt-4 border-t pt-4"
                        >
                            <div
                                class="mb-2 text-xs font-semibold text-muted-foreground uppercase"
                            >
                                Subdomains
                            </div>
                            <div class="space-y-2">
                                <div
                                    v-for="sub in subdomains.filter(
                                        (s) => s.domain.id === domain.id,
                                    )"
                                    :key="sub.id"
                                    class="flex items-center justify-between rounded bg-muted/50 p-2 text-sm"
                                >
                                    <span class="font-medium"
                                        >{{ sub.name }}.{{ domain.name }}</span
                                    >
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="text-xs text-muted-foreground"
                                            >{{ sub.runtime }}</span
                                        >
                                        <Link
                                            :href="`/hosting/subdomains/${sub.id}`"
                                            method="delete"
                                            as="button"
                                            class="text-destructive hover:text-destructive/80"
                                        >
                                            <Trash2 class="h-3.5 w-3.5" />
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <div
                    v-if="domains.length === 0"
                    class="rounded-xl border-2 border-dashed py-12 text-center"
                >
                    <Globe
                        class="mx-auto mb-4 h-12 w-12 text-muted-foreground"
                    />
                    <h3 class="font-medium">No domains found</h3>
                    <p class="text-sm text-muted-foreground">
                        Start by creating your first web domain.
                    </p>
                </div>
            </div>

            <div class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>Create Domain</CardTitle>
                        <CardDescription
                            >Add a new primary domain.</CardDescription
                        >
                    </CardHeader>
                    <CardContent>
                        <Form
                            action="/hosting/domains"
                            method="post"
                            class="space-y-4"
                        >
                            <div class="space-y-2">
                                <Label>Domain Name</Label>
                                <Input
                                    name="name"
                                    placeholder="example.com"
                                    required
                                />
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
                                        >
                                            {{ server.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <Label>Document Root</Label>
                                <Input
                                    name="document_root"
                                    value="/var/www/html"
                                    required
                                />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <Label>Runtime</Label>
                                    <select
                                        name="runtime"
                                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:outline-none"
                                        required
                                    >
                                        <option value="php">PHP</option>
                                        <option value="node">Node.js</option>
                                        <option value="ruby">Ruby</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <Label>Version</Label>
                                    <Input
                                        name="runtime_version"
                                        placeholder="8.3"
                                    />
                                </div>
                            </div>
                            <div class="space-y-2">
                                <Label>Git Repository (Optional)</Label>
                                <Input
                                    name="git_repository"
                                    placeholder="https://github.com/user/repo"
                                />
                            </div>
                            <div class="space-y-2">
                                <Label>Git Branch</Label>
                                <Input name="git_branch" value="main" />
                            </div>
                            <input
                                type="hidden"
                                name="web_server"
                                value="nginx"
                            />
                            <Button type="submit" class="w-full">
                                <Plus class="mr-2 h-4 w-4" /> Create Domain
                            </Button>
                        </Form>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Create Subdomain</CardTitle>
                        <CardDescription
                            >Add a subdomain to an existing
                            domain.</CardDescription
                        >
                    </CardHeader>
                    <CardContent>
                        <Form
                            action="/hosting/subdomains"
                            method="post"
                            class="space-y-4"
                        >
                            <div class="space-y-2">
                                <Label>Label</Label>
                                <div class="flex items-center gap-2">
                                    <Input
                                        name="name"
                                        placeholder="app"
                                        required
                                        class="flex-1"
                                    />
                                    <span class="text-sm text-muted-foreground"
                                        >.domain.com</span
                                    >
                                </div>
                            </div>
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
                                    >
                                        {{ server.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <Label>Document Root</Label>
                                <Input
                                    name="document_root"
                                    value="/var/www/sub"
                                    required
                                />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <Label>Runtime</Label>
                                    <select
                                        name="runtime"
                                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:outline-none"
                                        required
                                    >
                                        <option value="php">PHP</option>
                                        <option value="node">Node.js</option>
                                        <option value="ruby">Ruby</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <Label>Version</Label>
                                    <Input
                                        name="runtime_version"
                                        placeholder="20"
                                    />
                                </div>
                            </div>
                            <Button type="submit" class="w-full">
                                <Plus class="mr-2 h-4 w-4" /> Create Subdomain
                            </Button>
                        </Form>
                    </CardContent>
                </Card>
            </div>
        </div>

        <!-- SERVERS TAB -->
        <div v-if="activeTab === 'servers'" class="grid gap-8 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <Card v-for="server in managedServers" :key="server.id">
                    <CardHeader
                        class="flex flex-row items-center justify-between pb-2"
                    >
                        <div>
                            <CardTitle class="flex items-center gap-2">
                                <Server class="h-5 w-5 text-primary" />
                                {{ server.name }}
                            </CardTitle>
                            <CardDescription
                                >{{ server.host }}:{{
                                    server.port
                                }}</CardDescription
                            >
                        </div>
                        <Badge
                            :variant="
                                server.is_active ? 'default' : 'secondary'
                            "
                        >
                            {{ server.is_active ? 'Active' : 'Inactive' }}
                        </Badge>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-muted-foreground">
                                {{
                                    domains.filter(
                                        (d) =>
                                            d.managed_server.id === server.id,
                                    ).length
                                }}
                                domains hosted
                            </div>
                            <div class="flex items-center gap-2">
                                <Button
                                    variant="outline"
                                    size="sm"
                                    as-child
                                    title="Export Configuration"
                                >
                                    <a
                                        :href="`/hosting/servers/${server.id}/export`"
                                        target="_blank"
                                    >
                                        <Download class="mr-2 h-4 w-4" />
                                        Export
                                    </a>
                                </Button>
                                <Button variant="ghost" size="icon" as-child>
                                    <Link
                                        :href="`/hosting/servers/${server.id}`"
                                        method="delete"
                                        as="button"
                                    >
                                        <Trash2
                                            class="h-4 w-4 text-destructive"
                                        />
                                    </Link>
                                </Button>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <div
                    v-if="managedServers.length === 0"
                    class="rounded-xl border-2 border-dashed py-12 text-center"
                >
                    <Server
                        class="mx-auto mb-4 h-12 w-12 text-muted-foreground"
                    />
                    <h3 class="font-medium">No servers registered</h3>
                    <p class="text-sm text-muted-foreground">
                        Add a managed server to start deploying.
                    </p>
                </div>
            </div>

            <div>
                <Card>
                    <CardHeader>
                        <CardTitle>Register Server</CardTitle>
                        <CardDescription
                            >Connect a new managed web server.</CardDescription
                        >
                    </CardHeader>
                    <CardContent>
                        <Form
                            action="/hosting/servers"
                            method="post"
                            class="space-y-4"
                        >
                            <div class="space-y-2">
                                <Label>Server Name</Label>
                                <Input
                                    name="name"
                                    placeholder="Production 01"
                                    required
                                />
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-2 space-y-2">
                                    <Label>Host / IP</Label>
                                    <Input
                                        name="host"
                                        placeholder="192.168.1.1"
                                        required
                                    />
                                </div>
                                <div class="space-y-2">
                                    <Label>Port</Label>
                                    <Input
                                        name="port"
                                        type="number"
                                        value="22"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="space-y-2">
                                <Label>Agent Token</Label>
                                <Input
                                    name="agent_token"
                                    type="password"
                                    required
                                />
                            </div>
                            <input type="hidden" name="is_active" value="1" />
                            <Button type="submit" class="w-full">
                                <Plus class="mr-2 h-4 w-4" /> Add Server
                            </Button>
                        </Form>
                    </CardContent>
                </Card>
            </div>
        </div>

        <!-- USERS TAB -->
        <div v-if="activeTab === 'users'" class="grid gap-8 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="overflow-hidden rounded-xl border">
                    <table class="w-full text-sm">
                        <thead class="border-b bg-muted/50">
                            <tr>
                                <th class="p-4 text-left font-medium">User</th>
                                <th class="p-4 text-left font-medium">
                                    Domains
                                </th>
                                <th class="p-4 text-right font-medium">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <tr
                                v-for="user in users"
                                :key="user.id"
                                class="hover:bg-muted/30"
                            >
                                <td class="p-4">
                                    <div class="font-medium">
                                        {{ user.name }}
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        {{ user.email }}
                                    </div>
                                </td>
                                <td class="p-4">
                                    {{
                                        domains.filter(
                                            (d) => d.user.id === user.id,
                                        ).length
                                    }}
                                </td>
                                <td class="p-4 text-right">
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        as-child
                                    >
                                        <Link
                                            :href="`/hosting/users/${user.id}`"
                                            method="delete"
                                            as="button"
                                        >
                                            <Trash2
                                                class="h-4 w-4 text-destructive"
                                            />
                                        </Link>
                                    </Button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="users.length === 0"
                    class="rounded-xl border-2 border-dashed py-12 text-center"
                >
                    <User
                        class="mx-auto mb-4 h-12 w-12 text-muted-foreground"
                    />
                    <h3 class="font-medium">No users found</h3>
                </div>
            </div>

            <div>
                <Card>
                    <CardHeader>
                        <CardTitle>Create User</CardTitle>
                        <CardDescription
                            >Add a new hosting customer.</CardDescription
                        >
                    </CardHeader>
                    <CardContent>
                        <Form
                            action="/hosting/users"
                            method="post"
                            class="space-y-4"
                        >
                            <div class="space-y-2">
                                <Label>Name</Label>
                                <Input
                                    name="name"
                                    placeholder="John Doe"
                                    required
                                />
                            </div>
                            <div class="space-y-2">
                                <Label>Email</Label>
                                <Input
                                    name="email"
                                    type="email"
                                    placeholder="john@example.com"
                                    required
                                />
                            </div>
                            <div class="space-y-2">
                                <Label>Password</Label>
                                <Input
                                    name="password"
                                    type="password"
                                    required
                                />
                            </div>
                            <Button type="submit" class="w-full">
                                <Plus class="mr-2 h-4 w-4" /> Create User
                            </Button>
                        </Form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
