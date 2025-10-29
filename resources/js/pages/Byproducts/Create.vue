<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

interface Harvest {
    id: number;
    harvest_date: string;
    quantity: number;
    plot: {
        id: number;
        name: string;
    };
}

defineProps<{
    harvests?: Harvest[];
}>();

const form = useForm({
    harvest_id: '',
    name: '',
    description: '',
    quantity: '',
    unit: 'kg',
    price_per_unit: '',
    status: 'available',
});

const submit = () => {
    form.post('/byproducts');
};
</script>

<template>
    <AppLayout>
        <Head :title="t('byproduct.add_byproduct')" />

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="mb-8">
                    <Button
                        variant="ghost"
                        @click="$inertia.visit('/byproducts')"
                        class="mb-4"
                    >
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back to Byproducts
                    </Button>
                    <h2 class="text-3xl font-bold text-foreground">
                        {{ t('byproduct.add_byproduct') }}
                    </h2>
                    <p class="mt-1 text-muted-foreground">
                        Add a new coconut byproduct listing
                    </p>
                </div>

                <form
                    @submit.prevent="submit"
                    class="space-y-6 rounded-lg border border-border bg-card p-6"
                >
                    <div class="space-y-2">
                        <Label for="harvest_id">Source Harvest *</Label>
                        <select
                            id="harvest_id"
                            v-model="form.harvest_id"
                            required
                            class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                        >
                            <option value="">Select a harvest</option>
                            <option
                                v-for="harvest in harvests"
                                :key="harvest.id"
                                :value="harvest.id"
                            >
                                {{ harvest.plot.name }} -
                                {{
                                    new Date(
                                        harvest.harvest_date,
                                    ).toLocaleDateString()
                                }}
                                ({{ harvest.quantity }} coconuts)
                            </option>
                        </select>
                        <InputError :message="form.errors.harvest_id" />
                    </div>

                    <div class="space-y-2">
                        <Label for="name">{{ t('byproduct.name') }} *</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            placeholder="e.g., Coconut Husks, Coconut Shells, Coir Fiber"
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="space-y-2">
                        <Label for="description">{{
                            t('byproduct.description')
                        }}</Label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="3"
                            class="flex min-h-[60px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-base shadow-sm placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            placeholder="Describe the byproduct, quality, potential uses..."
                        />
                        <InputError :message="form.errors.description" />
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="quantity"
                                >{{ t('byproduct.quantity') }} *</Label
                            >
                            <Input
                                id="quantity"
                                v-model="form.quantity"
                                type="number"
                                step="0.01"
                                min="0"
                                required
                            />
                            <InputError :message="form.errors.quantity" />
                        </div>

                        <div class="space-y-2">
                            <Label for="unit"
                                >{{ t('byproduct.unit') }} *</Label
                            >
                            <select
                                id="unit"
                                v-model="form.unit"
                                required
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            >
                                <option value="kg">Kilograms (kg)</option>
                                <option value="tons">Tons</option>
                                <option value="bags">Bags</option>
                                <option value="bales">Bales</option>
                                <option value="pieces">Pieces</option>
                                <option value="bundles">Bundles</option>
                            </select>
                            <InputError :message="form.errors.unit" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label for="price_per_unit"
                            >{{ t('byproduct.price_per_unit') }} *</Label
                        >
                        <Input
                            id="price_per_unit"
                            v-model="form.price_per_unit"
                            type="number"
                            step="0.01"
                            min="0"
                            required
                            placeholder="0.00"
                        />
                        <InputError :message="form.errors.price_per_unit" />
                    </div>

                    <div class="space-y-2">
                        <Label for="status"
                            >{{ t('byproduct.status') }} *</Label
                        >
                        <select
                            id="status"
                            v-model="form.status"
                            required
                            class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                        >
                            <option value="available">Available</option>
                            <option value="reserved">Reserved</option>
                            <option value="sold">Sold</option>
                        </select>
                        <InputError :message="form.errors.status" />
                    </div>

                    <div class="flex justify-end gap-4 pt-6">
                        <Button
                            type="button"
                            variant="outline"
                            @click="$inertia.visit('/byproducts')"
                        >
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
