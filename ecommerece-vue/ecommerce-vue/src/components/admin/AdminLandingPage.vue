<template>
  <div class="flex min-h-screen bg-white">
    <!-- Sidebar Column -->
    <div class="w-64 sticky top-0 h-screen overflow-y-auto">
      <Sidebar />
    </div>
    
    <!-- Main Content Column -->
    <div class="flex-1 flex flex-col">
      <!-- Navbar -->
      <div class="h-16 bg-white sticky top-0 z-10">
        <Navbar />
      </div>
      
      <!-- Page Content -->
      <div class="flex-1 p-5">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script setup>
import Sidebar from './AdminSidebar.vue';
import Navbar from './AdminNavBarTop.vue';
</script>