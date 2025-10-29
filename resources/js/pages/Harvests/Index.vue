<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, Clock, Leaf, Package, Plus } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

interface Plot {
    id: number;
    name: string;
}

interface Harvest {
    id: number;
    plot: Plot;
    harvest_date: string;
    quantity: number;
    status: string;
    bid_start_time: string | null;
    bid_end_time: string | null;
    notes: string | null;
}

interface PaginatedHarvests {
    data: Harvest[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

defineProps<{
    harvests: PaginatedHarvests;
}>();

const getStatusColor = (
    status: string,
): 'default' | 'secondary' | 'destructive' | 'outline' => {
    const colors: Record<
        string,
        'default' | 'secondary' | 'destructive' | 'outline'
    > = {
        pending: 'secondary',
        active: 'default',
        completed: 'outline',
        cancelled: 'destructive',
    };
    return colors[status] || 'secondary';
};

const getStatusLabel = (status: string) => {
    return t(`harvest.${status}`);
};

const getTimeRemaining = (endTime: string | null) => {
    if (!endTime) return null;

    const now = new Date();
    const end = new Date(endTime);
    const diff = end.getTime() - now.getTime();

    if (diff <= 0) return 'Ended';

    const hours = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

    if (hours > 24) {
        const days = Math.floor(hours / 24);
        return `${days}d ${hours % 24}h`;
    }

    return `${hours}h ${minutes}m`;
};
</script>

<template>
    <AppLayout>
        <Head :title="t('harvest.harvests')" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-8 flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-foreground">
                            {{ t('harvest.harvests') }}
                        </h2>
                        <p class="mt-1 text-muted-foreground">
                            Manage your coconut harvests and bidding
                        </p>
                    </div>
                    <Link href="/harvests/create">
                        <Button>
                            <Plus class="mr-2 h-4 w-4" />
                            {{ t('harvest.add_harvest') }}
                        </Button>
                    </Link>
                </div>

                <!-- Harvests List -->
                <div v-if="harvests.data.length > 0" class="space-y-4">
                    <Link
                        v-for="harvest in harvests.data"
                        :key="harvest.id"
                        :href="`/harvests/${harvest.id}`"
                        class="block"
                    >
                        <div
                            class="rounded-lg border border-border bg-card p-6 transition-colors hover:border-primary"
                        >
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="mb-2 flex items-center gap-3">
                                        <h3
                                            class="text-lg font-semibold text-foreground"
                                        >
                                            {{ harvest.plot.name }}
                                        </h3>
                                        <Badge
                                            :variant="
                                                getStatusColor(harvest.status)
                                            "
                                        >
                                            {{ getStatusLabel(harvest.status) }}
                                        </Badge>
                                    </div>

                                    <div
                                        class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3"
                                    >
                                        <div
                                            class="flex items-center gap-2 text-muted-foreground"
                                        >
                                            <Calendar class="h-4 w-4" />
                                            <span class="text-sm">
                                                {{
                                                    new Date(
                                                        harvest.harvest_date,
                                                    ).toLocaleDateString()
                                                }}
                                            </span>
                                        </div>

                                        <div
                                            class="flex items-center gap-2 text-muted-foreground"
                                        >
                                            <Package class="h-4 w-4" />
                                            <span class="text-sm">
                                                {{ harvest.quantity }} coconuts
                                            </span>
                                        </div>

                                        <div
                                            v-if="
                                                harvest.bid_end_time &&
                                                harvest.status === 'active'
                                            "
                                            class="flex items-center gap-2 text-muted-foreground"
                                        >
                                            <Clock class="h-4 w-4" />
                                            <span
                                                class="text-sm font-medium text-primary"
                                            >
                                                {{
                                                    getTimeRemaining(
                                                        harvest.bid_end_time,
                                                    )
                                                }}
                                                remaining
                                            </span>
                                        </div>
                                    </div>

                                    <p
                                        v-if="harvest.notes"
                                        class="mt-3 line-clamp-2 text-sm text-muted-foreground"
                                    >
                                        {{ harvest.notes }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Empty State -->
                <div
                    v-else
                    class="rounded-lg border border-border bg-card p-12 text-center"
                >
                    <Leaf
                        class="mx-auto mb-4 h-16 w-16 text-muted-foreground"
                    />
                    <h3 class="mb-2 text-lg font-semibold text-foreground">
                        {{ t('common.no_data') }}
                    </h3>
                    <p class="mb-6 text-muted-foreground">
                        You haven't logged any harvests yet. Start by adding
                        your first harvest.
                    </p>
                    <Link href="/harvests/create">
                        <Button>
                            <Plus class="mr-2 h-4 w-4" />
                            {{ t('harvest.add_harvest') }}
                        </Button>
                    </Link>
                </div>

                <!-- Pagination -->
                <div
                    v-if="harvests.last_page > 1"
                    class="mt-6 flex justify-center gap-2"
                >
                    <Link
                        v-for="page in harvests.last_page"
                        :key="page"
                        :href="`/harvests?page=${page}`"
                        :class="[
                            'rounded-md px-4 py-2 text-sm font-medium transition-colors',
                            page === harvests.current_page
                                ? 'bg-primary text-primary-foreground'
                                : 'border border-border bg-card hover:bg-muted',
                        ]"
                    >
                        {{ page }}
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
