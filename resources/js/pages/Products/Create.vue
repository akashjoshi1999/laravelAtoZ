<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create a product',
        href: '/products/create',
    },
];
</script>

<template>
    <Head title="Create a product" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            
        </div>
    </AppLayout>
</template>
