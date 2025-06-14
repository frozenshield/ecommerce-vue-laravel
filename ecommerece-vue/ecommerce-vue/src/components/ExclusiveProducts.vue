<template>
  <div class="bg-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <!-- Header with tabs -->
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4 md:mb-0">Exclusive Products</h2>
        <div class="flex space-x-4 border-b border-gray-200 w-full md:w-auto">
          <button 
            v-for="(tab, index) in tabs" 
            :key="index"
            class="px-4 py-2 text-sm font-medium"
            :class="{
              'text-yellow-600 border-b-2 border-yellow-600': activeTab === index,
              'text-gray-500 hover:text-gray-700': activeTab !== index
            }"
            @click="activeTab = index"
          >
            {{ tab }}
          </button>
        </div>
      </div>

      <!-- Product Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div 
          v-for="(product, index) in filteredProducts" 
          :key="index"
          class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow"
        >
          <!-- Product Image -->
          <div class="bg-gray-100 h-48 flex items-center justify-center relative">
            <img 
              :src="`/images/products/${product.image}`" 
              :alt="product.name"
              class="max-h-full max-w-full object-contain p-4"
            >
            <!-- Badge -->
            <span 
              v-if="product.badge"
              class="absolute top-2 left-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded"
            >
              {{ product.badge }}
            </span>
          </div>

          <!-- Product Info -->
          <div class="p-4">
            <p class="text-xs text-gray-500 mb-1">{{ product.brand }}</p>
            <h3 class="font-medium text-gray-900 mb-2">{{ product.name }}</h3>
            
            <!-- Rating -->
            <div class="flex items-center mb-2">
              <div class="flex text-yellow-400">
                <i class="pi pi-star-fill text-xs" v-for="i in 5" :key="i"></i>
              </div>
              <span class="text-xs text-gray-500 ml-1">({{ product.reviews }})</span>
            </div>

            <!-- Price -->
            <div class="flex items-center">
              <span class="text-lg font-bold text-gray-900">${{ product.price }}</span>
              <span 
                v-if="product.originalPrice"
                class="text-sm text-gray-500 line-through ml-2"
              >
                ${{ product.originalPrice }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const tabs = ref(['New Arrival', 'Best Sellers', 'Special Offer']);
const activeTab = ref(0);

const products = ref([
  {
    image: 'accessory1.png',
    brand: 'STOUTER',
    name: 'Apple Watch Serial 7',
    reviews: 0,
    price: '1,088.18',
    category: 'new'
  },
  {
    image: 'accessory3.png',
    brand: 'GLOBAL OFFICE',
    name: 'Apple Keyboard',
    reviews: 0,
    price: '268.15',
    category: 'best'
  },
  {
    image: 'accessory2.png',
    brand: 'TPSON',
    name: 'Apple Airpods Serial 3',
    reviews: 0,
    price: '706.70',
    originalPrice: '1,279.79',
    category: 'special',
    badge: 'Sale'
  },
  {
    image: 'phone1.png',
    brand: 'STOUTER',
    name: 'Leather Watch in Black',
    reviews: 7,
    price: '178.18',
    category: 'best'
  },
  {
    image: 'headphone2.png',
    brand: 'YOUNG SHOP',
    name: 'Macbook Pro 2015 13 Inch',
    reviews: 10,
    price: '1,232.27',
    category: 'best'
  },
  {
    image: 'headphone1.png',
    brand: 'GOPRO',
    name: 'Sony WN-10000AAA Wireless Headphones',
    reviews: 10,
    price: '1,978.69',
    originalPrice: '1,657.60',
    category: 'special'
  },
  {
    image: 'headphone5.png',
    brand: "ROBERT'S STORE",
    name: 'iPad Pro 12.9-inch',
    reviews: 6,
    price: '1,382.72',
    category: 'new'
  },
  {
    image: 'headphone7.png',
    brand: "ROBERT'S STORE",
    name: 'iPad Pro 12.9-inch',
    reviews: 6,
    price: '1,382.72',
    category: 'new'
  },
  {
    image: 'headphone5.png',
    brand: "ROBERT'S STORE",
    name: 'iPad Pro 12.9-inch',
    reviews: 6,
    price: '1,382.72',
    category: 'new'
  },
]);

const filteredProducts = computed(() => {
  const categories = ['new', 'best', 'special'];
  return products.value.filter(product => product.category === categories[activeTab.value]);
});
</script>

<style scoped>
/* Smooth transitions */
button {
  transition: all 0.2s ease;
}
</style>