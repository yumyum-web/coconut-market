<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft, X } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

interface PlotImage {
    id: number;
    image_path: string;
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
}

const props = defineProps<{
    plot: Plot;
}>();

const form = useForm({
    name: props.plot.name,
    description: props.plot.description || '',
    size: props.plot.size.toString(),
    location: props.plot.location,
    tree_count: props.plot.tree_count?.toString() || '',
    potential_harvest: props.plot.potential_harvest?.toString() || '',
    harvest_frequency: props.plot.harvest_frequency,
    custom_frequency: props.plot.custom_frequency || '',
    is_harvest_sold: props.plot.is_harvest_sold,
    can_deliver: props.plot.can_deliver,
    available_categories: props.plot.available_categories,
    images: [] as File[],
    delete_images: [] as number[],
});

const categories = [
    { value: 'husked', label: t('bid.husked') },
    { value: 'unhusked', label: t('bid.unhusked') },
    { value: 'scraped', label: t('bid.scraped') },
];

const handleImageUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files) {
        form.images = Array.from(target.files).slice(0, 10);
    }
};

const markImageForDeletion = (imageId: number) => {
    if (!form.delete_images.includes(imageId)) {
        form.delete_images.push(imageId);
    }
};

const submit = () => {
    form.put(`/plots/${props.plot.id}`, {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout>
        <Head :title="t('plot.edit_plot')" />

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="mb-8">
                    <Button
                        variant="ghost"
                        @click="$inertia.visit('/plots')"
                        class="mb-4"
                    >
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back to Plots
                    </Button>
                    <h2 class="text-3xl font-bold text-foreground">
                        {{ t('plot.edit_plot') }}
                    </h2>
                    <p class="mt-1 text-muted-foreground">
                        Update your plot information
                    </p>
                </div>

                <form
                    @submit.prevent="submit"
                    class="space-y-6 rounded-lg border border-border bg-card p-6"
                >
                    <!-- Existing Images -->
                    <div v-if="plot.images.length > 0" class="space-y-2">
                        <Label>{{ t('plot.current_images') }}</Label>
                        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                            <div
                                v-for="image in plot.images"
                                :key="image.id"
                                class="group relative"
                            >
                                <img
                                    :src="`/storage/${image.image_path}`"
                                    alt="Plot image"
                                    class="h-24 w-full rounded-md object-cover"
                                    :class="{
                                        'opacity-50':
                                            form.delete_images.includes(
                                                image.id,
                                            ),
                                    }"
                                />
                                <Button
                                    v-if="
                                        !form.delete_images.includes(image.id)
                                    "
                                    type="button"
                                    variant="destructive"
                                    size="icon"
                                    class="absolute top-1 right-1 h-6 w-6 opacity-0 transition-opacity group-hover:opacity-100"
                                    @click="markImageForDeletion(image.id)"
                                >
                                    <X class="h-4 w-4" />
                                </Button>
                                <span
                                    v-else
                                    class="absolute inset-0 flex items-center justify-center text-xs font-semibold text-destructive"
                                >
                                    Will be deleted
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label for="name">{{ t('plot.name') }} *</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="space-y-2">
                        <Label for="description">{{
                            t('plot.description')
                        }}</Label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="3"
                            class="flex min-h-[60px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-base shadow-sm placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                        />
                        <InputError :message="form.errors.description" />
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="size">{{ t('plot.size') }} *</Label>
                            <Input
                                id="size"
                                v-model="form.size"
                                type="number"
                                step="0.01"
                                required
                            />
                            <InputError :message="form.errors.size" />
                        </div>

                        <div class="space-y-2">
                            <Label for="tree_count">{{
                                t('plot.tree_count')
                            }}</Label>
                            <Input
                                id="tree_count"
                                v-model="form.tree_count"
                                type="number"
                            />
                            <InputError :message="form.errors.tree_count" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label for="location">{{ t('plot.location') }} *</Label>
                        <Input
                            id="location"
                            v-model="form.location"
                            type="text"
                            required
                        />
                        <InputError :message="form.errors.location" />
                    </div>

                    <div class="space-y-2">
                        <Label for="potential_harvest">{{
                            t('plot.potential_harvest')
                        }}</Label>
                        <Input
                            id="potential_harvest"
                            v-model="form.potential_harvest"
                            type="number"
                        />
                        <InputError :message="form.errors.potential_harvest" />
                    </div>

                    <div class="space-y-2">
                        <Label for="harvest_frequency"
                            >{{ t('plot.harvest_frequency') }} *</Label
                        >
                        <select
                            id="harvest_frequency"
                            v-model="form.harvest_frequency"
                            class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                        >
                            <option value="weekly">
                                {{ t('plot.weekly') }}
                            </option>
                            <option value="biweekly">
                                {{ t('plot.biweekly') }}
                            </option>
                            <option value="monthly">
                                {{ t('plot.monthly') }}
                            </option>
                            <option value="custom">
                                {{ t('plot.custom') }}
                            </option>
                        </select>
                        <InputError :message="form.errors.harvest_frequency" />
                    </div>

                    <div
                        v-if="form.harvest_frequency === 'custom'"
                        class="space-y-2"
                    >
                        <Label for="custom_frequency">{{
                            t('plot.custom_frequency')
                        }}</Label>
                        <Input
                            id="custom_frequency"
                            v-model="form.custom_frequency"
                            type="text"
                            placeholder="e.g., Every 6 weeks"
                        />
                        <InputError :message="form.errors.custom_frequency" />
                    </div>

                    <div class="space-y-4">
                        <Label>{{ t('plot.available_categories') }}</Label>
                        <div class="space-y-2">
                            <div
                                v-for="category in categories"
                                :key="category.value"
                                class="flex items-center space-x-2"
                            >
                                <Checkbox
                                    :id="category.value"
                                    :checked="
                                        form.available_categories.includes(
                                            category.value,
                                        )
                                    "
                                    @update:checked="
                                        (checked: boolean) => {
                                            if (checked) {
                                                form.available_categories.push(
                                                    category.value,
                                                );
                                            } else {
                                                form.available_categories =
                                                    form.available_categories.filter(
                                                        (c) =>
                                                            c !==
                                                            category.value,
                                                    );
                                            }
                                        }
                                    "
                                />
                                <Label
                                    :for="category.value"
                                    class="cursor-pointer font-normal"
                                >
                                    {{ category.label }}
                                </Label>
                            </div>
                        </div>
                        <InputError
                            :message="form.errors.available_categories"
                        />
                    </div>

                    <div class="flex items-center space-x-2">
                        <Checkbox
                            id="is_harvest_sold"
                            :checked="form.is_harvest_sold"
                            @update:checked="
                                (checked: boolean) =>
                                    (form.is_harvest_sold = checked)
                            "
                        />
                        <Label
                            for="is_harvest_sold"
                            class="cursor-pointer font-normal"
                        >
                            {{ t('plot.is_harvest_sold') }}
                        </Label>
                    </div>

                    <div class="flex items-center space-x-2">
                        <Checkbox
                            id="can_deliver"
                            :checked="form.can_deliver"
                            @update:checked="
                                (checked: boolean) =>
                                    (form.can_deliver = checked)
                            "
                        />
                        <Label
                            for="can_deliver"
                            class="cursor-pointer font-normal"
                        >
                            {{ t('plot.can_deliver') }}
                        </Label>
                    </div>

                    <div class="space-y-2">
                        <Label for="images"
                            >{{ t('plot.add_new_images') }} (Max 10)</Label
                        >
                        <Input
                            id="images"
                            type="file"
                            accept="image/*"
                            multiple
                            @change="handleImageUpload"
                        />
                        <p class="text-xs text-muted-foreground">
                            Upload additional images of your plot
                        </p>
                        <InputError :message="form.errors.images" />
                    </div>

                    <div class="flex justify-end gap-4 pt-6">
                        <Button
                            type="button"
                            variant="outline"
                            @click="$inertia.visit('/plots')"
                        >
                            {{ t('common.cancel') }}
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            {{ t('common.save') }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
