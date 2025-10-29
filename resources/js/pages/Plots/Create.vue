<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import InputError from '@/components/InputError.vue';
import { ArrowLeft } from 'lucide-vue-next';

const { t } = useI18n();

const form = useForm({
    name: '',
    description: '',
    size: '',
    location: '',
    tree_count: '',
    potential_harvest: '',
    harvest_frequency: 'monthly',
    custom_frequency: '',
    is_harvest_sold: true,
    can_deliver: false,
    available_categories: [] as string[],
    images: [] as File[],
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

const submit = () => {
    form.post('/plots');
};
</script>

<template>
    <AppLayout>
        <Head :title="t('plot.add_plot')" />

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-8">
                    <Button variant="ghost" @click="$inertia.visit('/plots')" class="mb-4">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Back to Plots
                    </Button>
                    <h2 class="text-3xl font-bold text-foreground">
                        {{ t('plot.add_plot') }}
                    </h2>
                    <p class="text-muted-foreground mt-1">
                        Add a new coconut plot to your farm
                    </p>
                </div>

                <form @submit.prevent="submit" class="bg-card rounded-lg border border-border p-6 space-y-6">
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
                        <Label for="description">{{ t('plot.description') }}</Label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="3"
                            class="flex min-h-[60px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-base shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                        />
                        <InputError :message="form.errors.description" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                            <Label for="tree_count">{{ t('plot.tree_count') }}</Label>
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
                        <Label for="potential_harvest">{{ t('plot.potential_harvest') }}</Label>
                        <Input
                            id="potential_harvest"
                            v-model="form.potential_harvest"
                            type="number"
                        />
                        <InputError :message="form.errors.potential_harvest" />
                    </div>

                    <div class="space-y-2">
                        <Label for="harvest_frequency">{{ t('plot.harvest_frequency') }} *</Label>
                        <select
                            id="harvest_frequency"
                            v-model="form.harvest_frequency"
                            class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                        >
                            <option value="weekly">{{ t('plot.weekly') }}</option>
                            <option value="biweekly">{{ t('plot.biweekly') }}</option>
                            <option value="monthly">{{ t('plot.monthly') }}</option>
                            <option value="custom">{{ t('plot.custom') }}</option>
                        </select>
                        <InputError :message="form.errors.harvest_frequency" />
                    </div>

                    <div v-if="form.harvest_frequency === 'custom'" class="space-y-2">
                        <Label for="custom_frequency">{{ t('plot.custom_frequency') }}</Label>
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
                            <div v-for="category in categories" :key="category.value" class="flex items-center space-x-2">
                                <Checkbox
                                    :id="category.value"
                                    :checked="form.available_categories.includes(category.value)"
                                    @update:checked="(checked: boolean) => {
                                        if (checked) {
                                            form.available_categories.push(category.value);
                                        } else {
                                            form.available_categories = form.available_categories.filter(c => c !== category.value);
                                        }
                                    }"
                                />
                                <Label :for="category.value" class="font-normal cursor-pointer">
                                    {{ category.label }}
                                </Label>
                            </div>
                        </div>
                        <InputError :message="form.errors.available_categories" />
                    </div>

                    <div class="flex items-center space-x-2">
                        <Checkbox
                            id="is_harvest_sold"
                            :checked="form.is_harvest_sold"
                            @update:checked="(checked: boolean) => form.is_harvest_sold = checked"
                        />
                        <Label for="is_harvest_sold" class="font-normal cursor-pointer">
                            {{ t('plot.is_harvest_sold') }}
                        </Label>
                    </div>

                    <div class="flex items-center space-x-2">
                        <Checkbox
                            id="can_deliver"
                            :checked="form.can_deliver"
                            @update:checked="(checked: boolean) => form.can_deliver = checked"
                        />
                        <Label for="can_deliver" class="font-normal cursor-pointer">
                            {{ t('plot.can_deliver') }}
                        </Label>
                    </div>

                    <div class="space-y-2">
                        <Label for="images">{{ t('plot.images') }} (Max 10)</Label>
                        <Input
                            id="images"
                            type="file"
                            accept="image/*"
                            multiple
                            @change="handleImageUpload"
                        />
                        <p class="text-xs text-muted-foreground">Upload up to 10 images of your plot</p>
                        <InputError :message="form.errors.images" />
                    </div>

                    <div class="flex justify-end gap-4 pt-6">
                        <Button type="button" variant="outline" @click="$inertia.visit('/plots')">
                            {{ t('common.cancel') }}
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            {{ t('common.create') }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
