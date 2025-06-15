<template>
    <div>
        <div class="bg-white">
             <div style="position:relative; z-index:100;">
            <NavBarTop />
            <NavBarBottom />
             <HeroBanner />
            </div>
            <ServiceHighlight />
            <ProductDeals />
            <TopCategories />
            <PromoBanner />
            <ExclusiveProducts />
            <MobileDownload />
            <Feature />
            <HandPick />
            <Footer />
            
        </div>
    </div>
</template>

<script setup>
import NavBarTop from '../components/NavBarTop.vue';
import NavBarBottom from '../components/NavBarBottom.vue';
import HeroBanner from '../components/HeroBanner.vue';
import ServiceHighlight from '../components/ServiceHighlights.vue'
import ProductDeals from '../components/ProductDeals.vue';
import TopCategories from '../components/TopCategories.vue';
import PromoBanner from '../components/PromoBanners.vue';
import ExclusiveProducts from '../components/ExclusiveProducts.vue';
import MobileDownload from '../components/MobileDownload.vue';
import Footer from './Footers.vue';
import Feature from '../components/FeatureDeals.vue';
import HandPick from '../components/HandPick.vue';
</script>