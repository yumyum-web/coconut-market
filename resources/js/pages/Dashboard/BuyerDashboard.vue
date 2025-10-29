<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

interface Props {
    stats: {
        activeBids: number;
        wonBids: number;
    };
    activeBids: any[];
    wonBids: any[];
    activeHarvests: any[];
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
                        Welcome back! Find the best coconut harvests.
                    </p>
                </div>

                <!-- Stats Grid -->
                <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="rounded-lg border border-border bg-card p-6">
                        <div class="text-sm font-medium text-muted-foreground">
                            Active Bids
                        </div>
                        <div class="mt-2 text-3xl font-bold text-primary">
                            {{ stats.activeBids }}
                        </div>
                    </div>
                    <div class="rounded-lg border border-border bg-card p-6">
                        <div class="text-sm font-medium text-muted-foreground">
                            Won Bids
                        </div>
                        <div class="mt-2 text-3xl font-bold text-primary">
                            {{ stats.wonBids }}
                        </div>
                    </div>
                </div>

                <!-- Available Harvests -->
                <div class="mb-8 rounded-lg border border-border bg-card p-6">
                    <h3 class="mb-4 text-xl font-semibold">
                        Available Harvests
                    </h3>
                    <div
                        v-if="activeHarvests.length > 0"
                        class="grid grid-cols-1 gap-4 md:grid-cols-2"
                    >
                        <div
                            v-for="harvest in activeHarvests"
                            :key="harvest.id"
                            class="rounded-lg border border-border bg-muted/50 p-4 transition-colors hover:border-primary"
                        >
                            <div class="mb-3 flex items-start justify-between">
                                <div>
                                    <h4 class="font-medium">
                                        {{ harvest.plot.name }}
                                    </h4>
                                    <p class="text-sm text-muted-foreground">
                                        by {{ harvest.farmer.name }}
                                    </p>
                                </div>
                                <span
                                    class="rounded bg-primary/10 px-2 py-1 text-xs text-primary"
                                    >Active</span
                                >
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground"
                                        >Quantity:</span
                                    >
                                    <span class="font-medium"
                                        >{{
                                            harvest.total_quantity
                                        }}
                                        units</span
                                    >
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted-foreground"
                                        >Ends:</span
                                    >
                                    <span class="font-medium">{{
                                        new Date(
                                            harvest.bid_end_time,
                                        ).toLocaleString()
                                    }}</span>
                                </div>
                            </div>
                            <button
                                class="mt-4 w-full rounded bg-primary px-4 py-2 text-primary-foreground transition-colors hover:bg-primary/90"
                            >
                                Place Bid
                            </button>
                        </div>
                    </div>
                    <div v-else class="py-8 text-center text-muted-foreground">
                        No active harvests available
                    </div>
                </div>

                <!-- My Active Bids -->
                <div class="mb-8 rounded-lg border border-border bg-card p-6">
                    <h3 class="mb-4 text-xl font-semibold">My Active Bids</h3>
                    <div v-if="activeBids.length > 0" class="space-y-3">
                        <div
                            v-for="bid in activeBids"
                            :key="bid.id"
                            class="rounded-lg bg-muted/30 p-4"
                        >
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="font-medium">
                                        {{ bid.harvest.plot.name }}
                                    </h4>
                                    <p class="text-sm text-muted-foreground">
                                        Farmer: {{ bid.harvest.farmer.name }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-primary">
                                        ${{ bid.total_amount }}
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        Your Bid
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="py-8 text-center text-muted-foreground">
                        No active bids
                    </div>
                </div>

                <!-- Recently Won Bids -->
                <div class="rounded-lg border border-border bg-card p-6">
                    <h3 class="mb-4 text-xl font-semibold">Recently Won</h3>
                    <div v-if="wonBids.length > 0" class="space-y-3">
                        <div
                            v-for="bid in wonBids"
                            :key="bid.id"
                            class="rounded-lg border border-primary/20 bg-primary/5 p-4"
                        >
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="font-medium">
                                        {{ bid.harvest.plot.name }}
                                    </h4>
                                    <p class="text-sm text-muted-foreground">
                                        Farmer: {{ bid.harvest.farmer.name }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="rounded bg-primary px-2 py-1 text-xs text-primary-foreground"
                                        >Won</span
                                    >
                                    <div class="mt-1 font-semibold">
                                        ${{ bid.total_amount }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="py-8 text-center text-muted-foreground">
                        No won bids yet
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
