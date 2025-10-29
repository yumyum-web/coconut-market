<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Languages } from 'lucide-vue-next';
import { useI18n } from 'vue-i18n';

const { locale } = useI18n();

const languages = [
    { code: 'en', name: 'English' },
    // Add more languages here as needed
];

const setLanguage = (code: string) => {
    locale.value = code;
    localStorage.setItem('language', code);
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" size="icon" class="h-9 w-9">
                <Languages class="h-4 w-4" />
                <span class="sr-only">Change language</span>
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuItem
                v-for="lang in languages"
                :key="lang.code"
                @click="setLanguage(lang.code)"
                :class="{ 'bg-accent': locale === lang.code }"
            >
                {{ lang.name }}
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
