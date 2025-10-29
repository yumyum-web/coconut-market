<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { ArrowLeft, MapPin, Trees, Sprout, Calendar, Edit, Trash2, ChevronLeft, ChevronRight } from 'lucide-vue-next';

const { t } = useI18n();

interface PlotImage {
    id: number;
    image_path: string;
}

interface Harvest {
    id: number;
    harvest_date: string;
    quantity: number;
    status: string;
    bid_start_time: string | null;
    bid_end_time: string | null;
}

interface Plot {
    id: number;
    name: string;
    description: string | null;
    size: number;
    location: string;
    tree_count: number | null;
    potential_harvest: number | null;
    harvest_frequency: string;
    custom_frequency: string | null;
    is_harvest_sold: boolean;
    can_deliver: boolean;
    available_categories: string[];
    images: PlotImage[];
    harvests: Harvest[];
}

const props = defineProps<{
    plot: Plot;
}>();

const currentImageIndex = ref(0);

const nextImage = () => {
    if (props.plot.images.length > 0) {
        currentImageIndex.value = (currentImageIndex.value + 1) % props.plot.images.length;
    }
};

const prevImage = () => {
    if (props.plot.images.length > 0) {
        currentImageIndex.value = (currentImageIndex.value - 1 + props.plot.images.length) % props.plot.images.length;
    }
};

const deletePlot = () => {
    if (confirm('Are you sure you want to delete this plot?')) {
        router.delete(`/plots/${props.plot.id}`);
    }
};

const getStatusColor = (status: string) => {
    const colors = {
        pending: 'bg-yellow-500',
        active: 'bg-green-500',
        completed: 'bg-blue-500',
        cancelled: 'bg-red-500',
    };
    return colors[status as keyof typeof colors] || 'bg-gray-500';
};
</script>

<template>
    <AppLayout>
        <Head :title="plot.name" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-8 flex justify-between items-center">
                    <div>
                        <Button variant="ghost" @click="$inertia.visit('/plots')" class="mb-4">
                            <ArrowLeft class="w-4 h-4 mr-2" />
                            Back to Plots
                        </Button>
                        <h2 class="text-3xl font-bold text-foreground">
                            {{ plot.name }}
                        </h2>
                        <div class="flex items-center gap-2 mt-2 text-muted-foreground">
                            <MapPin class="w-4 h-4" />
                            <span>{{ plot.location }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <Link :href="`/plots/${plot.id}/edit`">
                            <Button variant="outline">
                                <Edit class="w-4 h-4 mr-2" />
                                {{ t('common.edit') }}
                            </Button>
                        </Link>
                        <Button variant="destructive" @click="deletePlot">
                            <Trash2 class="w-4 h-4 mr-2" />
                            {{ t('common.delete') }}
                        </Button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Image Gallery -->
                        <div class="bg-card rounded-lg border border-border overflow-hidden">
                            <div v-if="plot.images.length > 0" class="relative aspect-video bg-muted">
                                <img
                                    :src="`/storage/${plot.images[currentImageIndex].image_path}`"
                                    alt="Plot image"
                                    class="w-full h-full object-cover"
                                />
                                <div v-if="plot.images.length > 1" class="absolute inset-0 flex items-center justify-between p-4">
                                    <Button
                                        variant="secondary"
                                        size="icon"
                                        class="h-10 w-10 rounded-full"
                                        @click="prevImage"
                                    >
                                        <ChevronLeft class="h-5 w-5" />
                                    </Button>
                                    <Button
                                        variant="secondary"
                                        size="icon"
                                        class="h-10 w-10 rounded-full"
                                        @click="nextImage"
                                    >
                                        <ChevronRight class="h-5 w-5" />
                                    </Button>
                                </div>
                                <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2">
                                    <div
                                        v-for="(image, index) in plot.images"
                                        :key="image.id"
                                        class="w-2 h-2 rounded-full transition-colors"
                                        :class="index === currentImageIndex ? 'bg-white' : 'bg-white/50'"
                                    />
                                </div>
                            </div>
                            <div v-else class="aspect-video bg-muted flex items-center justify-center">
                                <Sprout class="w-16 h-16 text-muted-foreground" />
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="bg-card rounded-lg border border-border p-6">
                            <h3 class="text-lg font-semibold mb-4">{{ t('plot.description') }}</h3>
                            <p v-if="plot.description" class="text-muted-foreground">
                                {{ plot.description }}
                            </p>
                            <p v-else class="text-muted-foreground italic">
                                No description provided
                            </p>
                        </div>

                        <!-- Harvest History -->
                        <div class="bg-card rounded-lg border border-border p-6">
                            <h3 class="text-lg font-semibold mb-4">{{ t('harvest.harvest_history') }}</h3>
                            <div v-if="plot.harvests.length > 0" class="space-y-4">
                                <div
                                    v-for="harvest in plot.harvests"
                                    :key="harvest.id"
                                    class="flex items-center justify-between p-4 bg-muted rounded-lg"
                                >
                                    <div class="flex items-center gap-4">
                                        <div :class="[getStatusColor(harvest.status), 'w-3 h-3 rounded-full']" />
                                        <div>
                                            <div class="font-medium">{{ harvest.quantity }} coconuts</div>
                                            <div class="text-sm text-muted-foreground">
                                                {{ new Date(harvest.harvest_date).toLocaleDateString() }}
                                            </div>
                                        </div>
                                    </div>
                                    <Badge>{{ harvest.status }}</Badge>
                                </div>
                            </div>
                            <p v-else class="text-muted-foreground text-center py-8">
                                No harvests recorded yet
                            </p>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Stats -->
                        <div class="bg-card rounded-lg border border-border p-6">
                            <h3 class="text-lg font-semibold mb-4">{{ t('plot.details') }}</h3>
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                        <Trees class="w-5 h-5 text-primary" />
                                    </div>
                                    <div>
                                        <div class="text-sm text-muted-foreground">{{ t('plot.tree_count') }}</div>
                                        <div class="font-semibold">{{ plot.tree_count || 'N/A' }}</div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                        <Sprout class="w-5 h-5 text-primary" />
                                    </div>
                                    <div>
                                        <div class="text-sm text-muted-foreground">{{ t('plot.size') }}</div>
                                        <div class="font-semibold">{{ plot.size }} acres</div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                        <Calendar class="w-5 h-5 text-primary" />
                                    </div>
                                    <div>
                                        <div class="text-sm text-muted-foreground">{{ t('plot.harvest_frequency') }}</div>
                                        <div class="font-semibold capitalize">
                                            {{ plot.harvest_frequency === 'custom' ? plot.custom_frequency : plot.harvest_frequency }}
                                        </div>
                                    </div>
                                </div>

                                <div v-if="plot.potential_harvest" class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                        <Sprout class="w-5 h-5 text-primary" />
                                    </div>
                                    <div>
                                        <div class="text-sm text-muted-foreground">{{ t('plot.potential_harvest') }}</div>
                                        <div class="font-semibold">{{ plot.potential_harvest }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="bg-card rounded-lg border border-border p-6">
                            <h3 class="text-lg font-semibold mb-4">{{ t('plot.available_categories') }}</h3>
                            <div class="flex flex-wrap gap-2">
                                <Badge v-for="category in plot.available_categories" :key="category">
                                    {{ t(`bid.${category}`) }}
                                </Badge>
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="bg-card rounded-lg border border-border p-6">
                            <h3 class="text-lg font-semibold mb-4">Features</h3>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-muted-foreground">{{ t('plot.can_deliver') }}</span>
                                    <Badge :variant="plot.can_deliver ? 'default' : 'secondary'">
                                        {{ plot.can_deliver ? 'Yes' : 'No' }}
                                    </Badge>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-muted-foreground">{{ t('plot.is_harvest_sold') }}</span>
                                    <Badge :variant="plot.is_harvest_sold ? 'default' : 'secondary'">
                                        {{ plot.is_harvest_sold ? 'Yes' : 'No' }}
                                    </Badge>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <Link :href="`/harvests/create?plot_id=${plot.id}`">
                            <Button class="w-full">
                                {{ t('harvest.log_harvest') }}
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
