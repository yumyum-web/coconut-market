<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Plus, MapPin, Trees, Sprout } from 'lucide-vue-next';

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

const props = defineProps<Props>();
</script>

<template>
    <AppLayout>
        <Head :title="t('plot.plots')" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-3xl font-bold text-foreground">
                            {{ t('plot.plots') }}
                        </h2>
                        <p class="text-muted-foreground mt-1">
                            Manage your coconut plots and track their productivity
                        </p>
                    </div>
                    <Link href="/plots/create">
                        <Button>
                            <Plus class="w-4 h-4 mr-2" />
                            {{ t('plot.add_plot') }}
                        </Button>
                    </Link>
                </div>

                <div v-if="plots.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link
                        v-for="plot in plots.data"
                        :key="plot.id"
                        :href="`/plots/${plot.id}`"
                        class="group"
                    >
                        <div class="bg-card rounded-lg border border-border hover:border-primary transition-all overflow-hidden h-full">
                            <div v-if="plot.images.length > 0" class="aspect-video bg-muted overflow-hidden">
                                <img
                                    :src="`/storage/${plot.images[0].image_path}`"
                                    :alt="plot.name"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                />
                            </div>
                            <div v-else class="aspect-video bg-gradient-to-br from-primary/10 to-primary/5 flex items-center justify-center">
                                <Sprout class="w-16 h-16 text-primary/30" />
                            </div>
                            
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mb-2 group-hover:text-primary transition-colors">
                                    {{ plot.name }}
                                </h3>
                                <p v-if="plot.description" class="text-sm text-muted-foreground mb-4 line-clamp-2">
                                    {{ plot.description }}
                                </p>
                                
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center text-muted-foreground">
                                        <MapPin class="w-4 h-4 mr-2" />
                                        {{ plot.location }}
                                    </div>
                                    <div class="flex items-center text-muted-foreground">
                                        <Trees class="w-4 h-4 mr-2" />
                                        {{ plot.tree_count }} trees • {{ plot.size }} acres
                                    </div>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t border-border flex justify-between items-center text-sm">
                                    <span class="text-muted-foreground">
                                        {{ plot.harvests_count }} harvests
                                    </span>
                                    <span v-if="plot.can_deliver" class="px-2 py-1 bg-primary/10 text-primary text-xs rounded">
                                        Delivery Available
                                    </span>
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>

                <div v-else class="bg-card rounded-lg border border-border p-12 text-center">
                    <Sprout class="w-16 h-16 mx-auto text-muted-foreground mb-4" />
                    <h3 class="text-xl font-semibold mb-2">No plots yet</h3>
                    <p class="text-muted-foreground mb-6">
                        Start by adding your first coconut plot to begin tracking your harvests.
                    </p>
                    <Link href="/plots/create">
                        <Button>
                            <Plus class="w-4 h-4 mr-2" />
                            {{ t('plot.add_plot') }}
                        </Button>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
