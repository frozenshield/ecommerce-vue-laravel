<template>
  <div class="bg-white py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <!-- Section Title -->
      <h2 class="text-2xl font-bold text-gray-900 mb-6">Top Categories</h2>
      
      <!-- Categories Grid -->
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        <router-link 
          v-for="(category, index) in categories" 
          :key="index"
          :to="`/category/${category.slug}`"
          class="group flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-yellow-50 hover:border-yellow-200 transition-colors duration-200"
        >
          <!-- Category Image -->
          <div class="w-42 h-42 mb-3 flex items-center justify-center overflow-hidden">
            <img 
              :src="`/images/categories/${category.image}`" 
              :alt="category.name"
              class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-105"
            >
          </div>
          
          <!-- Category Name -->
          <h3 class="font-medium text-gray-900 text-center">{{ category.name }}</h3>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'; 

const categories = ref([
  { 
    name: 'Electronics', 
    image: 'category-electronics.png',  // Your image filename
    slug: 'electronics' 
  },
  { 
    name: 'Clothing', 
    image: 'category-clothing.png',
    slug: 'clothing' 
  },
  { 
    name: 'Computers', 
    image: 'category-computer.png',
    slug: 'computers' 
  },
  { 
    name: 'Home & Kitchen', 
    image: 'category-home.png',
    slug: 'home-kitchen' 
  },
  { 
    name: 'Health & Beauty', 
    image: 'category-beauty.png',
    slug: 'health-beauty' 
  },
  { 
    name: 'Jewelry & Watch', 
    image: 'category-jewelry.png',
    slug: 'jewelry-watch' 
  },
  { 
    name: 'Technology Toys', 
    image: 'category-technology.png',
    slug: 'technology-toys' 
  },
  { 
    name: 'Phones', 
    image: 'category-phones.png',
    slug: 'phones' ,
  },
  { 
    name: 'Foods', 
    image: 'category-food.png',
    slug: 'foods' 
  },
  { 
    name: 'Camera', 
    image: 'category-camera.png',
    slug: 'camera' 
  }
]);
</script>

<style scoped>
/* Smooth hover zoom effect */
img {
  transition: transform 0.3s ease;
}
</style>