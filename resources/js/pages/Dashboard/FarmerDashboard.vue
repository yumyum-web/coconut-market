<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

interface Props {
    stats: {
        plots: number;
        activeHarvests: number;
        products: number;
        byproducts: number;
    };
    activeHarvests: any[];
    scheduledHarvests: any[];
}

defineProps<Props>();
</script>

<template>
    <AppLayout>
        <Head :title="t('nav.dashboard')" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-foreground">
                        {{ t('nav.dashboard') }}
                    </h2>
                    <p class="mt-1 text-muted-foreground">
                        Welcome back! Here's an overview of your farm.
                    </p>
                </div>

                <!-- Stats Grid -->
                <div
                    class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4"
                >
                    <div class="rounded-lg border border-border bg-card p-6">
                        <div class="text-sm font-medium text-muted-foreground">
                            Total Plots
                        </div>
                        <div class="mt-2 text-3xl font-bold text-primary">
                            {{ stats.plots }}
                        </div>
                    </div>
                    <div class="rounded-lg border border-border bg-card p-6">
                        <div class="text-sm font-medium text-muted-foreground">
                            Active Harvests
                        </div>
                        <div class="mt-2 text-3xl font-bold text-primary">
                            {{ stats.activeHarvests }}
                        </div>
                    </div>
                    <div class="rounded-lg border border-border bg-card p-6">
                        <div class="text-sm font-medium text-muted-foreground">
                            Products
                        </div>
                        <div class="mt-2 text-3xl font-bold text-primary">
                            {{ stats.products }}
                        </div>
                    </div>
                    <div class="rounded-lg border border-border bg-card p-6">
                        <div class="text-sm font-medium text-muted-foreground">
                            Byproducts
                        </div>
                        <div class="mt-2 text-3xl font-bold text-primary">
                            {{ stats.byproducts }}
                        </div>
                    </div>
                </div>

                <!-- Active Harvests -->
                <div class="mb-8 rounded-lg border border-border bg-card p-6">
                    <h3 class="mb-4 text-xl font-semibold">
                        Active Harvest Bids
                    </h3>
                    <div v-if="activeHarvests.length > 0" class="space-y-4">
                        <div
                            v-for="harvest in activeHarvests"
                            :key="harvest.id"
                            class="rounded-lg bg-muted/50 p-4"
                        >
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="font-medium">
                                        {{ harvest.plot.name }}
                                    </h4>
                                    <p class="text-sm text-muted-foreground">
                                        {{ harvest.total_quantity }} units |
                                        {{ harvest.bids.length }} bids
                                    </p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-muted-foreground">
                                        Ends in
                                    </div>
                                    <div class="font-medium">
                                        {{
                                            new Date(
                                                harvest.bid_end_time,
                                            ).toLocaleString()
                                        }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="py-8 text-center text-muted-foreground">
                        No active harvest bids
                    </div>
                </div>

                <!-- Scheduled Harvests -->
                <div class="rounded-lg border border-border bg-card p-6">
                    <h3 class="mb-4 text-xl font-semibold">
                        Upcoming Harvests
                    </h3>
                    <div v-if="scheduledHarvests.length > 0" class="space-y-3">
                        <div
                            v-for="harvest in scheduledHarvests"
                            :key="harvest.id"
                            class="flex items-center justify-between rounded bg-muted/30 p-3"
                        >
                            <div>
                                <div class="font-medium">
                                    {{ harvest.plot.name }}
                                </div>
                                <div class="text-sm text-muted-foreground">
                                    {{ harvest.total_quantity }} units
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium">
                                    {{
                                        new Date(
                                            harvest.harvest_date,
                                        ).toLocaleDateString()
                                    }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="py-8 text-center text-muted-foreground">
                        No scheduled harvests
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
