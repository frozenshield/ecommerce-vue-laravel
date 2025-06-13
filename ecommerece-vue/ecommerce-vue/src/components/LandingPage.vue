<template>
    <div>
        <div class="bg-white">
            <NavBarTop />
            <div class="border-t-2 border-gray-200"></div>
            <NavBarBottom />
            <HeroBanner />
            <ServiceHighlight />
            <ProductDeals />
        </div>
       
    </div>
</template>

<script setup>
import HeroBanner from '../components/HeroBanner.vue';
import ServiceHighlight from '../components/ServiceHighlights.vue'
import ProductDeals from '../components/ProductDeals.vue';
</script>