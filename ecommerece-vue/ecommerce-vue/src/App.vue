<template>
    <div style="position:relative; z-index:100;">
        <NavBarTop />
        <NavBarBottom />
        <Footer />  
    </div>
    <router-view />
</template>

<script setup>
import NavBarTop from './components/NavBarTop.vue';
import NavBarBottom from './components/NavBarBottom.vue';
</script>