<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { MapPin, Plus, Sprout, Trees } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

interface Plot {
    id: number;
    name: string;
    description: string;
    size: number;
    location: string;
    tree_count: number;
    potential_harvest: number;
    can_deliver: boolean;
    images: any[];
    harvests_count: number;
}

interface Props {
    plots: {
        data: Plot[];
        current_page: number;
        last_page: number;
    };
}

defineProps<Props>();
</script>

<template>
    <AppLayout>
        <Head :title="t('plot.plots')" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-8 flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-foreground">
                            {{ t('plot.plots') }}
                        </h2>
                        <p class="mt-1 text-muted-foreground">
                            Manage your coconut plots and track their
                            productivity
                        </p>
                    </div>
                    <Link href="/plots/create">
                        <Button>
                            <Plus class="mr-2 h-4 w-4" />
                            {{ t('plot.add_plot') }}
                        </Button>
                    </Link>
                </div>

                <div
                    v-if="plots.data.length > 0"
                    class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                >
                    <Link
                        v-for="plot in plots.data"
                        :key="plot.id"
                        :href="`/plots/${plot.id}`"
                        class="group"
                    >
                        <div
                            class="h-full overflow-hidden rounded-lg border border-border bg-card transition-all hover:border-primary"
                        >
                            <div
                                v-if="plot.images.length > 0"
                                class="aspect-video overflow-hidden bg-muted"
                            >
                                <img
                                    :src="`/storage/${plot.images[0].image_path}`"
                                    :alt="plot.name"
                                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                />
                            </div>
                            <div
                                v-else
                                class="flex aspect-video items-center justify-center bg-gradient-to-br from-primary/10 to-primary/5"
                            >
                                <Sprout class="h-16 w-16 text-primary/30" />
                            </div>

                            <div class="p-6">
                                <h3
                                    class="mb-2 text-xl font-semibold transition-colors group-hover:text-primary"
                                >
                                    {{ plot.name }}
                                </h3>
                                <p
                                    v-if="plot.description"
                                    class="mb-4 line-clamp-2 text-sm text-muted-foreground"
                                >
                                    {{ plot.description }}
                                </p>

                                <div class="space-y-2 text-sm">
                                    <div
                                        class="flex items-center text-muted-foreground"
                                    >
                                        <MapPin class="mr-2 h-4 w-4" />
                                        {{ plot.location }}
                                    </div>
                                    <div
                                        class="flex items-center text-muted-foreground"
                                    >
                                        <Trees class="mr-2 h-4 w-4" />
                                        {{ plot.tree_count }} trees •
                                        {{ plot.size }} acres
                                    </div>
                                </div>

                                <div
                                    class="mt-4 flex items-center justify-between border-t border-border pt-4 text-sm"
                                >
                                    <span class="text-muted-foreground">
                                        {{ plot.harvests_count }} harvests
                                    </span>
                                    <span
                                        v-if="plot.can_deliver"
                                        class="rounded bg-primary/10 px-2 py-1 text-xs text-primary"
                                    >
                                        Delivery Available
                                    </span>
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>

                <div
                    v-else
                    class="rounded-lg border border-border bg-card p-12 text-center"
                >
                    <Sprout
                        class="mx-auto mb-4 h-16 w-16 text-muted-foreground"
                    />
                    <h3 class="mb-2 text-xl font-semibold">No plots yet</h3>
                    <p class="mb-6 text-muted-foreground">
                        Start by adding your first coconut plot to begin
                        tracking your harvests.
                    </p>
                    <Link href="/plots/create">
                        <Button>
                            <Plus class="mr-2 h-4 w-4" />
                            {{ t('plot.add_plot') }}
                        </Button>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
