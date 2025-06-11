<template>
  <div class="w-full mt-4">
    <Carousel :value="banners" :numVisible="1" :numScroll="1" :circular="true" :autoplayInterval="4000">
      <template #item="slotProps">
        <div class="relative h-[400px] w-full overflow-hidden rounded-xl shadow-md">
          <img
            :src="slotProps.data.image"
            alt="Banner"
            class="w-full h-full object-cover"
          />
          <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
            <h2 class="text-white text-3xl md:text-5xl font-bold drop-shadow-lg">
              {{ slotProps.data.title }}
            </h2>
          </div>
        </div>
      </template>
    </Carousel>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Carousel from 'primevue/carousel';

const banners = ref([
  {
    title: 'Super Sale up to 50%',
    image: '/images/banner1.jpg' // Replace with actual image paths
  },
  {
    title: 'New Arrivals 2025',
    image: '/images/banner2.jpg'
  }
]);
</script>
