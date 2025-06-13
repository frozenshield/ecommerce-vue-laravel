<template>
    <div class="bg-white py-8 px-4 sm:ps-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Deal of the day</h2>
                <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">End in:</span>
                        <div class="flex space-x-1 font-mono">
                            <span class="bg-gray-100 px-2 py-1 rounded">00</span>
                            <span>:</span>
                            <span class="bg-gray-100 px-2 py-1 rounded">00</span>
                            <span>:</span>
                            <span class="bg-gray-100 px-2 py-1 rounded">00</span>
                            <span>:</span>
                            <span class="bg-gray-100 px-2 py-1 rounded">00</span>
                        </div>       
                </div>
            </div>

            <!-- Product Carousel -->
            <Carousel
                :value="products"
                :numVisible="4"
                :numScroll="1"
                :circular="true"
                :showIndicators="true"
                :responsiveOptions="
                  [
                    { breakpoint: '1024px', numVisible: 3, numScroll: 1 },
                    { breakpoint: '768px', numVisible: 2, numScroll: 1 },
                    { breakpoint: '480px', numVisible: 1, numScroll: 1 }
                  ]
                "
            >
                <template #item="slotProps">
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow mx-2">
                    <!-- Product Image with Hover Overlay -->
                    <div class="relative bg-gray-100 h-40 mb-4 rounded-lg flex items-center justify-center overflow-hidden group">
                    <img
                        :src="slotProps.data.image"
                        :alt="slotProps.data.title"
                        class="object-contain h-full w-full"
                    />
                    <!-- Hover Overlay -->
                    <div
                        class="absolute inset-0 flex items-end justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                    >
                        <div class="flex space-x-4 mb-4">
                        <button class="bg-yellow-50 rounded-full p-2 shadow hover:bg-yellow-100">
                            <i class="pi pi-shopping-bag text-yellow-500 text-xl"></i>
                        </button>
                        <button class="bg-yellow-50 rounded-full p-2 shadow hover:bg-yellow-100">
                            <i class="pi pi-eye text-yellow-500 text-xl"></i>
                        </button>
                        <button class="bg-yellow-50 rounded-full p-2 shadow hover:bg-yellow-100">
                            <i class="pi pi-heart text-yellow-500 text-xl"></i>
                        </button>
                        <button class="bg-yellow-50 rounded-full p-2 shadow hover:bg-yellow-100">
                            <i class="pi pi-chart-bar text-yellow-500 text-xl"></i>
                        </button>
                        </div>
                    </div>
                    </div>

                    <!-- Price and Old Price -->
                    <div class="flex items-center space-x-2 mb-1">
                    <span class="font-bold text-green-700 text-lg">Php {{ slotProps.data.currentPrice }}</span>
                    <span class="text-gray-400 line-through text-sm">Php {{ slotProps.data.originalPrice }}</span>
                    </div>

                    <!-- Product Title -->
                    <h3 class="font-medium text-gray-900 mb-1">{{ slotProps.data.title }}</h3>
                    
                    <!-- Rating (Placeholder) -->
                    <div class="flex items-center mb-2">
                    <div class="flex text-yellow-400">
                        <i class="pi pi-star-fill" v-for="i in 5" :key="i"></i>
                    </div>
                    <span class="text-xs text-gray-500 ml-1">({{ slotProps.data.rating }})</span>
                    </div>

                    <!-- Progress Bar (example: percent based on sold, adjust as needed) -->
                    <div class="w-full h-2 bg-gray-200 rounded mb-1">
                    <div
                        class="h-2 bg-yellow-400 rounded"
                        :style="{ width: (slotProps.data.sold * 20) + '%' }"
                    ></div>
                    </div>

                    <!-- Vendor & Sales -->
                    <div class="text-xs text-gray-500">
                    <p>SOLD BY {{ slotProps.data.vendor }}</p>
                    <p>Sold: {{ slotProps.data.sold }}</p>
                    </div>
                    
                </div>
                </template>
            </Carousel>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Carousel from 'primevue/carousel';

const products = ref([
  {
    image: '/images/headphone1.png',
    currentPrice: '123.04',
    originalPrice: '774.93',
    title: 'Ground - Bluetooth Headphone',
    vendor: 'RUSSEL LUIS GEMENTIZA',
    sold: 4,
    rating: 4
  },
  {
    image: '/images/headphone2.png',
    currentPrice: '234.37',
    originalPrice: '234.39',
    title: 'Camera Samsung SS-24 (USPS)',
    vendor: "RUSSEL LUIS GEMENTIZA",
    sold: 4,
    rating: 4
  },
  {
    image: '/images/headphone3.png',
    currentPrice: '308.72',
    originalPrice: '302.62',
    title: 'MacBook Pro 2015',
    vendor: 'RUSSEL LUIS GEMENTIZA',
    sold: 3,
    rating: 5
  },
  {
    image: '/images/headphone4.png',
    currentPrice: '103.43',
    originalPrice: '75.50',
    title: 'Apple Watch Serial 7',
    vendor: 'RUSSEL LUIS GEMENTIZA',
    sold: 3,
    rating: 4
  },
  {
    image: '/images/headphone5.png',
    currentPrice: '103.43',
    originalPrice: '75.50',
    title: 'Apple Watch Serial 7',
    vendor: 'RUSSEL LUIS GEMENTIZA',
    sold: 3,
    rating: 4
  },
  {
    image: '/images/headphone6.png',
    currentPrice: '103.43',
    originalPrice: '75.50',
    title: 'Apple Watch Serial 7',
    vendor: 'RUSSEL LUIS GEMENTIZA',
    sold: 3,
    rating: 4
  },
  // Add more products as needed
]);
</script>