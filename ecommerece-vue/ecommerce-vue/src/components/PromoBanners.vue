<template>
  <div class="bg-white py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Dual Banner Container -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Left Banner (Fabric Bed) -->
        <div class="relative rounded-xl overflow-hidden shadow-lg">
          <img 
            src="/images/banner5.png" 
            alt="Fabric Bed" 
            class="w-full h-full object-cover min-h-[300px]"
          >
          <div class="absolute inset-0 bg-black/20 flex items-end p-6">
            <div class="bg-white/90 backdrop-blur-sm p-4 rounded-lg w-full">
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="text-xl font-bold text-gray-900">Razer Headset</h3>
                  <p class="text-red-600 font-semibold">DISCOUNT 25% OFF</p>
                  <p class="text-sm text-gray-600 mt-1">
                    Group numbers support with to avoid data process supply and increase metrics
                  </p>
                </div>
                <div class="text-right">
                  <p class="text-gray-500 line-through">Php 250.00</p>
                  <p class="text-2xl font-bold text-gray-900">Php13,219.05</p>
                  <p class="text-xs text-gray-500">Price Year</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Banner (Pioneer Speaker) -->
        <div class="relative rounded-xl overflow-hidden shadow-lg">
          <img 
            src="/images/banner4.png" 
            alt="Pioneer Speaker" 
            class="w-full h-full object-cover min-h-[300px]"
          >
          <div class="absolute inset-0 bg-black/20 flex items-end p-6">
            <div class="bg-white/90 backdrop-blur-sm p-4 rounded-lg w-full">
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="text-xl font-bold text-gray-900">Rejuv</h3>
                  <p class="text-sm text-gray-600 italic">
                    Uninstall by Champion Series 2020® + June 2017
                  </p>
                  <p class="text-xs text-gray-500 mt-1">
                    Subwebsite by Pioneer
                  </p>
                </div>
                <div class="text-right">
                  <p class="text-gray-500 line-through">Php280.00</p>
                  <p class="text-2xl font-bold text-gray-900">Php250.00</p>
                  <p class="text-xs text-gray-500">Source:</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// No reactive data needed for static banners
</script>

<style scoped>
/* Smooth transitions for hover effects */
img {
  transition: transform 0.3s ease;
}
</style>