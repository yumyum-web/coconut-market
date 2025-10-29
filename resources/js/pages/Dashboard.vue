<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth?.user);

// Redirect will be handled by the server-side DashboardController
// This page should not be directly rendered
</script>

<template>
    <div class="flex items-center justify-center min-h-screen">
        <div class="text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto"></div>
            <p class="mt-4 text-muted-foreground">Loading dashboard...</p>
        </div>
    </div>
</template>
