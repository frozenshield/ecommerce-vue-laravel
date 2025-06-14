<template>
    <div>
        <div class="bg-white">
            <HeroBanner />
            <ServiceHighlight />
            <ProductDeals />
            <TopCategories />
            <PromoBanner />
            <ExclusiveProducts />
            <MobileDownload />
            <Feature />
            <HandPick />
            
        </div>
    </div>
</template>

<script setup>
import HeroBanner from '../components/HeroBanner.vue';
import ServiceHighlight from '../components/ServiceHighlights.vue'
import ProductDeals from '../components/ProductDeals.vue';
import TopCategories from '../components/TopCategories.vue';
import PromoBanner from '../components/PromoBanners.vue';
import ExclusiveProducts from '../components/ExclusiveProducts.vue';
import MobileDownload from '../components/MobileDownload.vue';
import Footer from '../components/Footer.vue';
import Feature from '../components/FeatureDeals.vue';
import HandPick from '../components/HandPick.vue';
</script>