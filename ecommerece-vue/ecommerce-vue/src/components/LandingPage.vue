<template>
    <div>
        <div class="bg-black">
            <NavBarTop />
            <div class="border-b-2 border-gray-200"></div>
            <NavBarBottom />
            <HeroBanner />
        </div>
       

        <section class="bg-white py-10 px-4 md:px-20">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <img src="https://via.placeholder.com/600x400" alt="Hero Image" class="round-xl shadow-md" />

            </div> 
        </section>
    </div>
</template>

<script setup>
import HeroBanner from '@/components/HeroBanner.vue';
</script>