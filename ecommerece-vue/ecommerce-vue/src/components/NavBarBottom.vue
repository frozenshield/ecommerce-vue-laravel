<template>
  <!-- Compressed Bottom Navbar -->
  <div class="bg-yellow-500 text-gray-900 px-4 py-2 shadow">
    <div class="max-w-7xl mx-auto flex justify-between items-center text-sm">
      <!-- Left Links - Compressed -->
      <div class="flex items-center space-x-6">
        <router-link
          to="/departments"
          class="flex items-center hover:text-white transition-colors duration-200"
        >
          <i class="pi pi-bars mr-1 text-sm"></i>
          <span class="whitespace-nowrap">Shop by Department</span>
        </router-link>

        <div class="flex space-x-4">
          <router-link 
            to="/" 
            class="hover:text-white transition-colors duration-200 whitespace-nowrap"
          >
            Home
          </router-link>
          <router-link 
            to="/shop" 
            class="hover:text-white transition-colors duration-200 whitespace-nowrap"
          >
            Shop
          </router-link>
          <router-link 
            to="/pages" 
            class="hover:text-white transition-colors duration-200 whitespace-nowrap"
          >
            Pages
          </router-link>
          <router-link 
            to="/blog" 
            class="hover:text-white transition-colors duration-200 whitespace-nowrap"
          >
            Blog
          </router-link>
        </div>
      </div>

      <!-- Right Section - Compressed -->
      <div class="flex items-center space-x-4">
        <router-link 
          to="/track" 
          class="flex items-center hover:text-white transition-colors duration-200 whitespace-nowrap"
        >
          <i class="pi pi-map-marker mr-1 text-sm"></i>
          <span>Track Order</span>
        </router-link>

        <!-- Compact Dropdowns -->
        <div class="flex space-x-2">
          <Dropdown
            :options="currencies"
            optionLabel="label"
            class="w-24 bg-transparent border-none text-black text-sm"
            v-model="selectedCurrency"
            dropdownIcon="pi pi-chevron-down"
          >
            <template #value="slotProps">
              <div class="text-black whitespace-nowrap">
                {{ slotProps.value.code || slotProps.value.label }}
              </div>
            </template>
          </Dropdown>

          <Dropdown
            :options="languages"
            optionLabel="label"
            class="w-20 bg-transparent border-none text-black text-sm"
            v-model="selectedLanguage"
            dropdownIcon="pi pi-chevron-down"
          >
            <template #value="slotProps">
              <div class="text-black whitespace-nowrap">
                {{ slotProps.value.code || slotProps.value.label }}
              </div>
            </template>
          </Dropdown>
        </div>
      </div>
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