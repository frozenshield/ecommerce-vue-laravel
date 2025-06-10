<template>
  <NavBarTop />
  <NavBarBottom />
  <router-view />
</template>

<script setup>
import NavBarTop from './components/NavBarTop.vue';
import NavBarBottom from './components/NavBarBottom.vue';
</script>