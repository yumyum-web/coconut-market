<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Plus, ShoppingBasket } from 'lucide-vue-next';
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

interface Product {
    id: number;
    name: string;
    description: string | null;
    quantity: number;
    unit: string;
    price_per_unit: number;
    status: string;
    harvest: Harvest;
}

interface PaginatedProducts {
    data: Product[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

defineProps<{
    products: PaginatedProducts;
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
        <Head :title="t('product.products')" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-8 flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-foreground">
                            {{ t('product.products') }}
                        </h2>
                        <p class="mt-1 text-muted-foreground">
                            Manage your processed coconut products
                        </p>
                    </div>
                    <Link href="/products/create">
                        <Button>
                            <Plus class="mr-2 h-4 w-4" />
                            {{ t('product.add_product') }}
                        </Button>
                    </Link>
                </div>

                <!-- Products Grid -->
                <div
                    v-if="products.data.length > 0"
                    class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                >
                    <Link
                        v-for="product in products.data"
                        :key="product.id"
                        :href="`/products/${product.id}`"
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
                                        {{ product.name }}
                                    </h3>
                                    <p class="text-sm text-muted-foreground">
                                        From: {{ product.harvest.plot.name }}
                                    </p>
                                </div>
                                <Badge
                                    :variant="getStatusColor(product.status)"
                                >
                                    {{ product.status }}
                                </Badge>
                            </div>

                            <p
                                v-if="product.description"
                                class="mb-4 line-clamp-2 text-sm text-muted-foreground"
                            >
                                {{ product.description }}
                            </p>

                            <div class="space-y-2 border-t border-border pt-4">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-sm text-muted-foreground"
                                        >{{ t('product.quantity') }}</span
                                    >
                                    <span class="font-medium"
                                        >{{ product.quantity }}
                                        {{ product.unit }}</span
                                    >
                                </div>
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-sm text-muted-foreground"
                                        >{{ t('product.price_per_unit') }}</span
                                    >
                                    <span class="font-semibold text-primary">{{
                                        formatPrice(product.price_per_unit)
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
                                            product.quantity *
                                                product.price_per_unit,
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
                    <ShoppingBasket
                        class="mx-auto mb-4 h-16 w-16 text-muted-foreground"
                    />
                    <h3 class="mb-2 text-lg font-semibold text-foreground">
                        {{ t('common.no_data') }}
                    </h3>
                    <p class="mb-6 text-muted-foreground">
                        You haven't added any products yet. Start by creating
                        your first product listing.
                    </p>
                    <Link href="/products/create">
                        <Button>
                            <Plus class="mr-2 h-4 w-4" />
                            {{ t('product.add_product') }}
                        </Button>
                    </Link>
                </div>

                <!-- Pagination -->
                <div
                    v-if="products.last_page > 1"
                    class="mt-6 flex justify-center gap-2"
                >
                    <Link
                        v-for="page in products.last_page"
                        :key="page"
                        :href="`/products?page=${page}`"
                        :class="[
                            'rounded-md px-4 py-2 text-sm font-medium transition-colors',
                            page === products.current_page
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
