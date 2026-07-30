<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { Save, ArrowLeft } from '@lucide/vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface ManagedServerItem {
    id: number;
    name: string;
    host: string;
    port: number;
    agent_token: string;
    is_active: boolean;
}

const props = defineProps<{
    managedServer: ManagedServerItem;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Hosting', href: '/hosting' },
            { title: 'Edit Server' },
        ],
    },
});
</script>

<template>
    <Head :title="`Edit Server: ${managedServer.name}`" />

    <div class="mx-auto flex w-full max-w-2xl flex-col gap-8 p-6">
        <div class="flex items-center justify-between">
            <Link href="/hosting" class="flex items-center text-sm text-muted-foreground hover:text-foreground">
                <ArrowLeft class="mr-2 h-4 w-4" />
                Back to Dashboard
            </Link>
        </div>

        <Heading
            :title="`Edit Server: ${managedServer.name}`"
            description="Update managed server connection details and agent configuration."
        />

        <Card>
            <CardHeader>
                <CardTitle>Server Configuration</CardTitle>
                <CardDescription>Modify the connection settings for this server.</CardDescription>
            </CardHeader>
            <CardContent>
                <Form :action="`/hosting/servers/${managedServer.id}`" method="patch" class="space-y-6">
                    <div class="space-y-2">
                        <Label>Server Name</Label>
                        <Input name="name" :value="managedServer.name" required />
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2 space-y-2">
                            <Label>Host / IP</Label>
                            <Input name="host" :value="managedServer.host" required />
                        </div>
                        <div class="space-y-2">
                            <Label>Port</Label>
                            <Input name="port" type="number" :value="managedServer.port" required />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label>Agent Token</Label>
                        <Input
                            name="agent_token"
                            type="password"
                            placeholder="Keep empty to leave unchanged"
                        />
                        <p class="text-xs text-muted-foreground">For security, the token is not displayed.</p>
                    </div>

                    <div class="flex items-center space-x-2">
                        <input
                            type="checkbox"
                            name="is_active"
                            id="is_active"
                            :checked="managedServer.is_active"
                            value="1"
                            class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                        />
                        <Label for="is_active">Server is Active</Label>
                    </div>

                    <Button type="submit" class="w-full">
                        <Save class="mr-2 h-4 w-4" /> Save Changes
                    </Button>
                </Form>
            </CardContent>
        </Card>
    </div>
</template>
