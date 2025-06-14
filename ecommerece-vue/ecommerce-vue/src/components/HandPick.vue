<template>
  <div class="bg-white py-8">
    <!-- Dynamic Category Banner -->
    <div class="max-w-7xl mx-auto">
      <h3 class="text-center text-4xl mb-6">Handpick Favourites</h3> 
      
      <div class="flex flex-wrap justify-center gap-4 mb-10">
        <button 
          v-for="(category, index) in categories" 
          :key="index"
          @click="activeCategory = index"
          class="px-6 py-2 rounded-full font-medium transition-colors"
          :class="{
            'bg-yellow-500 text-white': activeCategory === index,
            'bg-gray-100 text-gray-700 hover:bg-gray-200': activeCategory !== index
          }"
        >
          {{ category.name }}
        </button>
      </div>

      <img 
        :src="activeBanner" 
        :alt="categories[activeCategory].name + ' banner'"
        class="w-full h-full object-cover"
      >
      <div class="absolute inset-0 flex items-center justify-center">
        <div class="text-center text-white px-4">
          <p class="text-lg md:text-xl max-w-2xl mx-auto">
            {{ categories[activeCategory].description }}
          </p>
        </div>
      </div>
    </div>


    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Category Navigation -->
      

      <!-- Product Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 py-4">
            <div 
            v-for="(product, index) in filteredProducts" 
            :key="index"
            class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow"
            >
                <!-- Product Image -->
                <div class="bg-gray-100 h-48 flex items-center justify-center p-4">
                    <img 
                    :src="`/images/products/${product.image}`" 
                    :alt="product.name"
                    class="max-h-full max-w-full object-contain"
                    >
                </div>
                
                <!-- Product Info -->
                <div class="p-4">
                    <h3 class="font-bold text-gray-900 mb-2">{{ product.name }}</h3>
                    <a 
                    href="#" 
                    class="inline-block text-yellow-600 font-medium text-sm hover:text-yellow-700"
                    >
                    Learn more →
                    </a>
                </div>
            </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const activeCategory = ref(0);

const categories = ref([
  {
    name: 'Learning essentials',
    banner: '/images/banners/handpick-learning.png'
  },
  {
    name: 'Video & content creation',
    banner: '/images/banners/handpick-content.png'
  },
  {
    name: 'Gaming',
    banner: '/images/banners/handpick-gaming.png'
  },
  {
    name: 'Health & wellbeing',
    banner: '/images/banners/handpick-health.png'
  }
]);

const activeBanner = computed(() => categories.value[activeCategory.value].banner);

const filteredProducts = computed(() => {
  return products.value.filter(
    product => product.category === activeCategory.value
  );
});

const products = ref([
  {
    name: 'Galaxy Z Flip6',
    image: 'phone1.png',
    category: 2
  },
  {
    name: 'Galaxy A56 5G',
    image: 'phone2.png',
    category: 2
  },
  {
    name: 'Galaxy Tab A9 LTE',
    image: 'phone3.png',
    category: 2
  },
  {
    name: 'Music Frame',
    image: 'console1.png',
    category: 2
  }
]);
</script>

<style scoped>
/* Smooth transitions */
button {
  transition: all 0.2s ease;
}
</style>