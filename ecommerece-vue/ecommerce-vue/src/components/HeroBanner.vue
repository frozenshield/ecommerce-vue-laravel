<template>
    <div  class="w-full mt-4 px-4 md:px-0">
      <div class="max-w-7xl mx-auto">
        <Carousel
          :value="banners"
          :numVisible="1"
          :numScroll="1"
          :circular="true"
          :autoplayInterval="4000"
          :showIndicators="true"
          class="carousel-custom"
          >
            <template #item="slotProps">
              <div class="relative h-[300px] md:h-[400px] w-full overflo-hidden rounded-xl shadow-lg">
                <img
                  :src="slotProps.data.image"
                  alt="Banner"
                  class="w-full h-full object-cover object-center"
                />
                <div class="absolute inset-cover object-center"/>
                  <div class="absolute inset-0 bg-gradient-to-r from-black/40 to-black/20 flex items-center px-8">
                    <div class="max-w-2xl">
                        <h2 class="text-white text-2xl md:text-4xl font-bold mb-2">
                        {{ slotProps.data.title }}
                      </h2>
                      <p v-if="slotProps.data.subtitle" class="text-white/90 text-lg">
                        {{ slotProps.data.subtitle }}
                      </p>
                      <Button 
                        v-if="slotProps.data.buttonText"
                        :label="slotProps.data.buttonText" 
                        class="mt-4 p-button-rounded p-button-outlined" 
                      />
                    </div>
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
import Button from 'primevue/button';

const banners = ref([
  {
    image: '/images/banner1.jpg',
    title: 'Summer Collection',
    subtitle: 'Discover our new arrivals',
    buttonText: 'Shop Now'
  },
  {
    image: '/images/banner2.jpg',
    title: 'Limited Time Offer',
    subtitle: 'Get 30% off selected items',
    buttonText: 'View Deals'
  }
]);
</script>

