<script setup lang="ts">
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import ThemeSwitcher from '@/components/ThemeSwitcher.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import {
    Gavel,
    LayoutGrid,
    Leaf,
    Package,
    ShoppingBasket,
    ShoppingCart,
    Sprout,
} from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const isFarmer = computed(() => user.value?.user_type === 'farmer');
const isBuyer = computed(() => user.value?.user_type === 'buyer');

const mainNavItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [
        {
            title: 'Dashboard',
            href: dashboard(),
            icon: LayoutGrid,
        },
    ];

    if (isFarmer.value) {
        items.push(
            {
                title: 'My Plots',
                href: '/plots',
                icon: Sprout,
            },
            {
                title: 'Harvests',
                href: '/harvests',
                icon: Leaf,
            },
            {
                title: 'Products',
                href: '/products',
                icon: ShoppingBasket,
            },
            {
                title: 'Byproducts',
                href: '/byproducts',
                icon: Package,
            },
        );
    }

    if (isBuyer.value) {
        items.push(
            {
                title: 'Marketplace',
                href: '/harvests',
                icon: ShoppingCart,
            },
            {
                title: 'My Bids',
                href: '/harvest-bids',
                icon: Gavel,
            },
        );
    }

    return items;
});
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <div class="flex items-center gap-2 px-2 py-2">
                <ThemeSwitcher />
                <LanguageSwitcher />
            </div>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
