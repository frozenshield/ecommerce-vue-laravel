<template>
    <aside class="w-64 bg-gray-100 h-screen shadow-md flex flex-col justify-between p-4">
      <!-- profile -->
        <div>
            <div class="flex items-center gap-3 mb-6 mt-12">
                <img src="https://i.pravatar.cc/40?img=3" alt="User Avatar" class="rounded-full w-10 h-10" />
                <div>
                    <p class="text-sm text-gray-400">Hello,</p>
                    <p class="font-semibold text-gray-800">RLG</p>
                </div>
            </div>

            <div class="mb-12">
                <p class="text-sm text-gray-400 mb-1">Earning</p>
                <p class="text-2xl font-bold text-gray-800">P 0.00 </p>
            </div>

            <ul class="space-y-3">
                <li v-for="item in menuItems" :key="item.label">
                    <router-link
                    :to="item.route"
                    class="flex items-center text-2xl gap-3 text-gray-700 hover:text-yellow-500 transition-colors"
                    >
                    <i :class="item.icon" class="text-lg"></i>
                    <span>{{ item.label }}</span>
                    </router-link>
                </li>
            </ul>
        </div>  
        
        <div class="text-sm text-gray-400 mt-6">
            <p class="font-bold text-black">
                <span class="text-yellow-500">russel</span>luis
            </p>
            <p>2025 RLG marketplace.</p>
        </div>
    </aside>
</template>

<script setup lang="ts">

import { ref } from 'vue'


const menuItems = ref([
  { label: 'Dashboard', route: '/landingpage', icon: 'pi pi-home' },
  { label: 'Products', route: '/products', icon: 'pi pi-box' },
  { label: 'Orders', route: '/orders', icon: 'pi pi-shopping-cart' },
  { label: 'Customers', route: '/customers', icon: 'pi pi-users' },
  { label: 'Coupons', route: '/coupons', icon: 'pi pi-tags' },
  { label: 'Categories', route: '/categories', icon: 'pi pi-list' },
  { label: 'Settings', route: '/settings', icon: 'pi pi-cog' }
])

</script>
