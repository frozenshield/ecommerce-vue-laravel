<template>
     <div class="bg-yellow-400 text-gray-900 px-6 py-2 flex justify-between items-center text-sm shadow">
        <!-- Left section -->
        <div class="flex items-center space-x-8">
            <router-link
                to="/departments"
                class="flex items-center font-white transition-colors duration-200">
                <i class="pi pi-bars mr-2"></i>
                Shop by Department
            </router-link>

            <div class="flex space-x-6 font-medium">
                <router-link 
                    to="/" 
                    class="hover:text-white transition-colors duration-200"
                >
                    Home
                </router-link>
                <router-link 
                    to="/shop" 
                    class="hover:text-white transition-colors duration-200"
                >
                    Shop
                </router-link>
                <router-link 
                    to="/pages" 
                    class="hover:text-white transition-colors duration-200"
                >
                    Pages
                </router-link>
                <router-link 
                    to="/blog" 
                    class="hover:text-white transition-colors duration-200"
                >
                    Blog
                </router-link>
            </div>
        </div>

        <!-- Right section -->
        <div class="flex items-center space-x-6">
            <router-link 
                to="/track" 
                class="flex items-center hover:text-white transition-colors duration-200"
            >
                <i class="pi pi-map-marker mr-2"></i>
                Track your order
            </router-link>

            <Dropdown
                :options="currencies"
                optionLabel="label"
                class="w-28 bg-transparent border-none text-black"
                v-model="selectedCurrency"
                dropdownIcon="pi pi-chevron-down"
            >
                <template #value="slotProps">
                    <div class="text-black">
                        {{ slotProps.value.label }}
                    </div>
                </template>
                <template #option="slotProps">
                    <div class="text-gray-900">
                        {{ slotProps.option.label }}
                    </div>
                </template>
            </Dropdown>
            
            <Dropdown
                :options="languages"
                optionLabel="label"
                class="w-28 bg-transparent border-none text-black"
                v-model="selectedLanguage"
                dropdownIcon="pi pi-chevron-down"
            >
                <template #value="slotProps">
                    <div class="text-black">
                        {{ slotProps.value.label }}
                    </div>
                </template>
                <template #option="slotProps">
                    <div class="text-gray-900">
                        {{ slotProps.option.label }}
                    </div>
                </template>
            </Dropdown>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import Button from 'primevue/button'
import Dropdown from 'primevue/dropdown'

const currencies = ref([
    { label: 'USD' },
    { label: 'PHP' },
    { label: 'EUR' }
])

const languages = ref([
    { label: 'English' },
    { label: 'Filipino' }
])

const selectedCurrency = ref(currencies.value[0])
const selectedLanguage = ref(languages.value[0])
</script>

<style scoped>
/* Remove the white color override */
.p-dropdown {
    backdrop-filter: blur(10px);
}

/* Smooth transitions */
.router-link-active {
    color: #fff;
    font-weight: 600;
}
</style>