<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import {
    ArrowLeft,
    Calendar,
    ChevronLeft,
    ChevronRight,
    Edit,
    MapPin,
    Sprout,
    Trash2,
    Trees,
} from 'lucide-vue-next';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

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
        currentImageIndex.value =
            (currentImageIndex.value + 1) % props.plot.images.length;
    }
};

const prevImage = () => {
    if (props.plot.images.length > 0) {
        currentImageIndex.value =
            (currentImageIndex.value - 1 + props.plot.images.length) %
            props.plot.images.length;
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
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-8 flex items-center justify-between">
                    <div>
                        <Button
                            variant="ghost"
                            @click="$inertia.visit('/plots')"
                            class="mb-4"
                        >
                            <ArrowLeft class="mr-2 h-4 w-4" />
                            Back to Plots
                        </Button>
                        <h2 class="text-3xl font-bold text-foreground">
                            {{ plot.name }}
                        </h2>
                        <div
                            class="mt-2 flex items-center gap-2 text-muted-foreground"
                        >
                            <MapPin class="h-4 w-4" />
                            <span>{{ plot.location }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <Link :href="`/plots/${plot.id}/edit`">
                            <Button variant="outline">
                                <Edit class="mr-2 h-4 w-4" />
                                {{ t('common.edit') }}
                            </Button>
                        </Link>
                        <Button variant="destructive" @click="deletePlot">
                            <Trash2 class="mr-2 h-4 w-4" />
                            {{ t('common.delete') }}
                        </Button>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- Main Content -->
                    <div class="space-y-6 lg:col-span-2">
                        <!-- Image Gallery -->
                        <div
                            class="overflow-hidden rounded-lg border border-border bg-card"
                        >
                            <div
                                v-if="plot.images.length > 0"
                                class="relative aspect-video bg-muted"
                            >
                                <img
                                    :src="`/storage/${plot.images[currentImageIndex].image_path}`"
                                    alt="Plot image"
                                    class="h-full w-full object-cover"
                                />
                                <div
                                    v-if="plot.images.length > 1"
                                    class="absolute inset-0 flex items-center justify-between p-4"
                                >
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
                                <div
                                    class="absolute right-0 bottom-4 left-0 flex justify-center gap-2"
                                >
                                    <div
                                        v-for="(image, index) in plot.images"
                                        :key="image.id"
                                        class="h-2 w-2 rounded-full transition-colors"
                                        :class="
                                            index === currentImageIndex
                                                ? 'bg-white'
                                                : 'bg-white/50'
                                        "
                                    />
                                </div>
                            </div>
                            <div
                                v-else
                                class="flex aspect-video items-center justify-center bg-muted"
                            >
                                <Sprout
                                    class="h-16 w-16 text-muted-foreground"
                                />
                            </div>
                        </div>

                        <!-- Description -->
                        <div
                            class="rounded-lg border border-border bg-card p-6"
                        >
                            <h3 class="mb-4 text-lg font-semibold">
                                {{ t('plot.description') }}
                            </h3>
                            <p
                                v-if="plot.description"
                                class="text-muted-foreground"
                            >
                                {{ plot.description }}
                            </p>
                            <p v-else class="text-muted-foreground italic">
                                No description provided
                            </p>
                        </div>

                        <!-- Harvest History -->
                        <div
                            class="rounded-lg border border-border bg-card p-6"
                        >
                            <h3 class="mb-4 text-lg font-semibold">
                                {{ t('harvest.harvest_history') }}
                            </h3>
                            <div
                                v-if="plot.harvests.length > 0"
                                class="space-y-4"
                            >
                                <div
                                    v-for="harvest in plot.harvests"
                                    :key="harvest.id"
                                    class="flex items-center justify-between rounded-lg bg-muted p-4"
                                >
                                    <div class="flex items-center gap-4">
                                        <div
                                            :class="[
                                                getStatusColor(harvest.status),
                                                'h-3 w-3 rounded-full',
                                            ]"
                                        />
                                        <div>
                                            <div class="font-medium">
                                                {{ harvest.quantity }} coconuts
                                            </div>
                                            <div
                                                class="text-sm text-muted-foreground"
                                            >
                                                {{
                                                    new Date(
                                                        harvest.harvest_date,
                                                    ).toLocaleDateString()
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                    <Badge>{{ harvest.status }}</Badge>
                                </div>
                            </div>
                            <p
                                v-else
                                class="py-8 text-center text-muted-foreground"
                            >
                                No harvests recorded yet
                            </p>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Stats -->
                        <div
                            class="rounded-lg border border-border bg-card p-6"
                        >
                            <h3 class="mb-4 text-lg font-semibold">
                                {{ t('plot.details') }}
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10"
                                    >
                                        <Trees class="h-5 w-5 text-primary" />
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm text-muted-foreground"
                                        >
                                            {{ t('plot.tree_count') }}
                                        </div>
                                        <div class="font-semibold">
                                            {{ plot.tree_count || 'N/A' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10"
                                    >
                                        <Sprout class="h-5 w-5 text-primary" />
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm text-muted-foreground"
                                        >
                                            {{ t('plot.size') }}
                                        </div>
                                        <div class="font-semibold">
                                            {{ plot.size }} acres
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10"
                                    >
                                        <Calendar
                                            class="h-5 w-5 text-primary"
                                        />
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm text-muted-foreground"
                                        >
                                            {{ t('plot.harvest_frequency') }}
                                        </div>
                                        <div class="font-semibold capitalize">
                                            {{
                                                plot.harvest_frequency ===
                                                'custom'
                                                    ? plot.custom_frequency
                                                    : plot.harvest_frequency
                                            }}
                                        </div>
                                    </div>
                                </div>

                                <div
                                    v-if="plot.potential_harvest"
                                    class="flex items-center gap-3"
                                >
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10"
                                    >
                                        <Sprout class="h-5 w-5 text-primary" />
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm text-muted-foreground"
                                        >
                                            {{ t('plot.potential_harvest') }}
                                        </div>
                                        <div class="font-semibold">
                                            {{ plot.potential_harvest }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div
                            class="rounded-lg border border-border bg-card p-6"
                        >
                            <h3 class="mb-4 text-lg font-semibold">
                                {{ t('plot.available_categories') }}
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <Badge
                                    v-for="category in plot.available_categories"
                                    :key="category"
                                >
                                    {{ t(`bid.${category}`) }}
                                </Badge>
                            </div>
                        </div>

                        <!-- Features -->
                        <div
                            class="rounded-lg border border-border bg-card p-6"
                        >
                            <h3 class="mb-4 text-lg font-semibold">Features</h3>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-muted-foreground">{{
                                        t('plot.can_deliver')
                                    }}</span>
                                    <Badge
                                        :variant="
                                            plot.can_deliver
                                                ? 'default'
                                                : 'secondary'
                                        "
                                    >
                                        {{ plot.can_deliver ? 'Yes' : 'No' }}
                                    </Badge>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-muted-foreground">{{
                                        t('plot.is_harvest_sold')
                                    }}</span>
                                    <Badge
                                        :variant="
                                            plot.is_harvest_sold
                                                ? 'default'
                                                : 'secondary'
                                        "
                                    >
                                        {{
                                            plot.is_harvest_sold ? 'Yes' : 'No'
                                        }}
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
