<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Package, Plus } from 'lucide-vue-next';

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

const props = defineProps<{
    byproducts: PaginatedByproducts;
}>();

const getStatusColor = (status: string): 'default' | 'secondary' | 'destructive' | 'outline' => {
    const colors: Record<string, 'default' | 'secondary' | 'destructive' | 'outline'> = {
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
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-8 flex justify-between items-center">
                    <div>
                        <h2 class="text-3xl font-bold text-foreground">
                            {{ t('byproduct.byproducts') }}
                        </h2>
                        <p class="text-muted-foreground mt-1">
                            Manage coconut byproducts (husks, shells, coir, etc.)
                        </p>
                    </div>
                    <Link href="/byproducts/create">
                        <Button>
                            <Plus class="w-4 h-4 mr-2" />
                            {{ t('byproduct.add_byproduct') }}
                        </Button>
                    </Link>
                </div>

                <!-- Byproducts Grid -->
                <div v-if="byproducts.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link
                        v-for="byproduct in byproducts.data"
                        :key="byproduct.id"
                        :href="`/byproducts/${byproduct.id}`"
                        class="block"
                    >
                        <div class="bg-card rounded-lg border border-border p-6 hover:border-primary transition-colors h-full">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-foreground mb-1">
                                        {{ byproduct.name }}
                                    </h3>
                                    <p class="text-sm text-muted-foreground">
                                        From: {{ byproduct.harvest.plot.name }}
                                    </p>
                                </div>
                                <Badge :variant="getStatusColor(byproduct.status)">
                                    {{ byproduct.status }}
                                </Badge>
                            </div>

                            <p v-if="byproduct.description" class="text-sm text-muted-foreground mb-4 line-clamp-2">
                                {{ byproduct.description }}
                            </p>

                            <div class="space-y-2 pt-4 border-t border-border">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-muted-foreground">{{ t('byproduct.quantity') }}</span>
                                    <span class="font-medium">{{ byproduct.quantity }} {{ byproduct.unit }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-muted-foreground">{{ t('byproduct.price_per_unit') }}</span>
                                    <span class="font-semibold text-primary">{{ formatPrice(byproduct.price_per_unit) }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-border">
                                    <span class="text-sm font-medium">Total Value</span>
                                    <span class="font-bold text-lg">{{ formatPrice(byproduct.quantity * byproduct.price_per_unit) }}</span>
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Empty State -->
                <div v-else class="bg-card rounded-lg border border-border p-12 text-center">
                    <Package class="w-16 h-16 mx-auto text-muted-foreground mb-4" />
                    <h3 class="text-lg font-semibold text-foreground mb-2">
                        {{ t('common.no_data') }}
                    </h3>
                    <p class="text-muted-foreground mb-6">
                        You haven't added any byproducts yet. Start by creating your first byproduct listing.
                    </p>
                    <Link href="/byproducts/create">
                        <Button>
                            <Plus class="w-4 h-4 mr-2" />
                            {{ t('byproduct.add_byproduct') }}
                        </Button>
                    </Link>
                </div>

                <!-- Pagination -->
                <div v-if="byproducts.last_page > 1" class="mt-6 flex justify-center gap-2">
                    <Link
                        v-for="page in byproducts.last_page"
                        :key="page"
                        :href="`/byproducts?page=${page}`"
                        :class="[
                            'px-4 py-2 rounded-md text-sm font-medium transition-colors',
                            page === byproducts.current_page
                                ? 'bg-primary text-primary-foreground'
                                : 'bg-card border border-border hover:bg-muted'
                        ]"
                    >
                        {{ page }}
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
