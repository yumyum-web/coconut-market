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

interface Plot {
    id: number;
    name: string;
    location: string;
}

interface BidTimeWindow {
    id: number;
    name: string;
    duration_hours: number;
}

interface HarvestCategory {
    id: number;
    name: string;
}

defineProps<{
    plots: Plot[];
    timeWindows: BidTimeWindow[];
    categories: HarvestCategory[];
}>();

const form = useForm({
    plot_id: '',
    harvest_date: '',
    quantity: '',
    notes: '',
    bid_time_window_id: '',
    category_prices: {} as Record<number, string>,
});

const submit = () => {
    form.post('/harvests');
};
</script>

<template>
    <AppLayout>
        <Head :title="t('harvest.add_harvest')" />

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="mb-8">
                    <Button
                        variant="ghost"
                        @click="$inertia.visit('/harvests')"
                        class="mb-4"
                    >
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back to Harvests
                    </Button>
                    <h2 class="text-3xl font-bold text-foreground">
                        {{ t('harvest.add_harvest') }}
                    </h2>
                    <p class="mt-1 text-muted-foreground">
                        Log a new coconut harvest from your plots
                    </p>
                </div>

                <form
                    @submit.prevent="submit"
                    class="space-y-6 rounded-lg border border-border bg-card p-6"
                >
                    <div class="space-y-2">
                        <Label for="plot_id">{{ t('plot.title') }} *</Label>
                        <select
                            id="plot_id"
                            v-model="form.plot_id"
                            required
                            class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                        >
                            <option value="">Select a plot</option>
                            <option
                                v-for="plot in plots"
                                :key="plot.id"
                                :value="plot.id"
                            >
                                {{ plot.name }} - {{ plot.location }}
                            </option>
                        </select>
                        <InputError :message="form.errors.plot_id" />
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="harvest_date"
                                >{{ t('harvest.harvest_date') }} *</Label
                            >
                            <Input
                                id="harvest_date"
                                v-model="form.harvest_date"
                                type="date"
                                required
                            />
                            <InputError :message="form.errors.harvest_date" />
                        </div>

                        <div class="space-y-2">
                            <Label for="quantity"
                                >{{ t('harvest.total_quantity') }} *</Label
                            >
                            <Input
                                id="quantity"
                                v-model="form.quantity"
                                type="number"
                                min="1"
                                required
                                placeholder="Number of coconuts"
                            />
                            <InputError :message="form.errors.quantity" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label for="notes">{{ t('harvest.notes') }}</Label>
                        <textarea
                            id="notes"
                            v-model="form.notes"
                            rows="3"
                            class="flex min-h-[60px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-base shadow-sm placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            placeholder="Any additional notes about this harvest..."
                        />
                        <InputError :message="form.errors.notes" />
                    </div>

                    <div class="border-t border-border pt-6">
                        <h3 class="mb-4 text-lg font-semibold">
                            Bidding Setup (Optional)
                        </h3>
                        <p class="mb-4 text-sm text-muted-foreground">
                            Configure bidding settings if you want to auction
                            this harvest. You can also start bidding later.
                        </p>

                        <div class="space-y-2">
                            <Label for="bid_time_window_id">{{
                                t('harvest.bid_time_window')
                            }}</Label>
                            <select
                                id="bid_time_window_id"
                                v-model="form.bid_time_window_id"
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                            >
                                <option value="">
                                    Don't start bidding yet
                                </option>
                                <option
                                    v-for="window in timeWindows"
                                    :key="window.id"
                                    :value="window.id"
                                >
                                    {{ window.name }}
                                </option>
                            </select>
                            <InputError
                                :message="form.errors.bid_time_window_id"
                            />
                        </div>

                        <div
                            v-if="form.bid_time_window_id"
                            class="mt-6 space-y-4"
                        >
                            <Label>Minimum Bid Prices by Category</Label>
                            <div
                                v-for="category in categories"
                                :key="category.id"
                                class="space-y-2"
                            >
                                <Label
                                    :for="`category-${category.id}`"
                                    class="text-sm font-normal"
                                >
                                    {{ category.name }}
                                </Label>
                                <Input
                                    :id="`category-${category.id}`"
                                    v-model="form.category_prices[category.id]"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    placeholder="Minimum price per unit"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 pt-6">
                        <Button
                            type="button"
                            variant="outline"
                            @click="$inertia.visit('/harvests')"
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
