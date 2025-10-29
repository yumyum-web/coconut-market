<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Package, Plus } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

interface Harvest {
    id: number;
    harvest_date: string;
    plot: {
        id: number;
        name: string;
    };
}

interface Byproduct {
    id: number;
    name: string;
    description: string | null;
    quantity: number;
    unit: string;
    price_per_unit: number;
    status: string;
    harvest: Harvest;
}

interface PaginatedByproducts {
    data: Byproduct[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

defineProps<{
    byproducts: PaginatedByproducts;
}>();

const getStatusColor = (
    status: string,
): 'default' | 'secondary' | 'destructive' | 'outline' => {
    const colors: Record<
        string,
        'default' | 'secondary' | 'destructive' | 'outline'
    > = {
        available: 'default',
        sold: 'outline',
        reserved: 'secondary',
    };
    return colors[status] || 'secondary';
};

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};
</script>

<template>
    <AppLayout>
        <Head :title="t('byproduct.byproducts')" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-8 flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-foreground">
                            {{ t('byproduct.byproducts') }}
                        </h2>
                        <p class="mt-1 text-muted-foreground">
                            Manage coconut byproducts (husks, shells, coir,
                            etc.)
                        </p>
                    </div>
                    <Link href="/byproducts/create">
                        <Button>
                            <Plus class="mr-2 h-4 w-4" />
                            {{ t('byproduct.add_byproduct') }}
                        </Button>
                    </Link>
                </div>

                <!-- Byproducts Grid -->
                <div
                    v-if="byproducts.data.length > 0"
                    class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                >
                    <Link
                        v-for="byproduct in byproducts.data"
                        :key="byproduct.id"
                        :href="`/byproducts/${byproduct.id}`"
                        class="block"
                    >
                        <div
                            class="h-full rounded-lg border border-border bg-card p-6 transition-colors hover:border-primary"
                        >
                            <div class="mb-4 flex items-start justify-between">
                                <div class="flex-1">
                                    <h3
                                        class="mb-1 text-lg font-semibold text-foreground"
                                    >
                                        {{ byproduct.name }}
                                    </h3>
                                    <p class="text-sm text-muted-foreground">
                                        From: {{ byproduct.harvest.plot.name }}
                                    </p>
                                </div>
                                <Badge
                                    :variant="getStatusColor(byproduct.status)"
                                >
                                    {{ byproduct.status }}
                                </Badge>
                            </div>

                            <p
                                v-if="byproduct.description"
                                class="mb-4 line-clamp-2 text-sm text-muted-foreground"
                            >
                                {{ byproduct.description }}
                            </p>

                            <div class="space-y-2 border-t border-border pt-4">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-sm text-muted-foreground"
                                        >{{ t('byproduct.quantity') }}</span
                                    >
                                    <span class="font-medium"
                                        >{{ byproduct.quantity }}
                                        {{ byproduct.unit }}</span
                                    >
                                </div>
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-sm text-muted-foreground"
                                        >{{
                                            t('byproduct.price_per_unit')
                                        }}</span
                                    >
                                    <span class="font-semibold text-primary">{{
                                        formatPrice(byproduct.price_per_unit)
                                    }}</span>
                                </div>
                                <div
                                    class="flex items-center justify-between border-t border-border pt-2"
                                >
                                    <span class="text-sm font-medium"
                                        >Total Value</span
                                    >
                                    <span class="text-lg font-bold">{{
                                        formatPrice(
                                            byproduct.quantity *
                                                byproduct.price_per_unit,
                                        )
                                    }}</span>
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
                    <Package
                        class="mx-auto mb-4 h-16 w-16 text-muted-foreground"
                    />
                    <h3 class="mb-2 text-lg font-semibold text-foreground">
                        {{ t('common.no_data') }}
                    </h3>
                    <p class="mb-6 text-muted-foreground">
                        You haven't added any byproducts yet. Start by creating
                        your first byproduct listing.
                    </p>
                    <Link href="/byproducts/create">
                        <Button>
                            <Plus class="mr-2 h-4 w-4" />
                            {{ t('byproduct.add_byproduct') }}
                        </Button>
                    </Link>
                </div>

                <!-- Pagination -->
                <div
                    v-if="byproducts.last_page > 1"
                    class="mt-6 flex justify-center gap-2"
                >
                    <Link
                        v-for="page in byproducts.last_page"
                        :key="page"
                        :href="`/byproducts?page=${page}`"
                        :class="[
                            'rounded-md px-4 py-2 text-sm font-medium transition-colors',
                            page === byproducts.current_page
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
