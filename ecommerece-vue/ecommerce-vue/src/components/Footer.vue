<template>
  <footer class="bg-gray-100 text-gray-700 py-12 px-4">
    <div class="max-w-7xl mx-auto">
      <!-- Newsletter Section -->
      <div class="mb-12 pb-8 border-b border-gray-300">
        <h2 class="text-2xl font-bold text-center mb-4">Join Our Newsletter Now</h2>
        <p class="text-center mb-6">Subscribe to get information about products and coupons</p>
        
        <div class="max-w-md mx-auto flex">
          <input
            type="email"
            placeholder="Email address"
            class="flex-1 px-4 py-3 border border-gray-300 rounded-l-md focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
          >
          <button
            class="bg-yellow-500 text-white px-6 py-3 rounded-r-md hover:bg-yellow-600 transition-colors whitespace-nowrap"
          >
            Subscribe
          </button>
        </div>
      </div>

      <!-- Footer Columns -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Contact Column -->
        <div>
          <h3 class="text-lg font-semibold mb-4">Contact us</h3>
          <ul class="space-y-3">
            <li class="flex items-start">
              <i class="pi pi-phone mr-3 mt-1"></i>
              <div>
                <p class="font-medium">Call us 24/7</p>
                <p>09569973694</p>
              </div>
            </li>
            <li class="flex items-start">
              <i class="pi pi-map-marker mr-3 mt-1"></i>
              <div>
                <p>11 Pabillare st Brgy. Damayan</p>
                <p>Quezon City, Metro Manila</p>
              </div>
            </li>
            <li class="flex items-start">
              <i class="pi pi-envelope mr-3 mt-1"></i>
              <p>russelluisg@gmail.com</p>
            </li>
          </ul>
        </div>

        <!-- Quick Links Column -->
        <div>
          <h3 class="text-lg font-semibold mb-4">Quick links</h3>
          <ul class="space-y-2">
            <li v-for="(link, index) in quickLinks" :key="index">
              <a href="#" class="hover:text-yellow-600 transition-colors">{{ link }}</a>
            </li>
          </ul>
        </div>

        <!-- Company/Business Columns -->
        <div class="grid grid-cols-2 gap-8">
          <div>
            <h3 class="text-lg font-semibold mb-4">Company</h3>
            <ul class="space-y-2">
              <li v-for="(item, index) in companyLinks" :key="index">
                <a href="#" class="hover:text-yellow-600 transition-colors">{{ item }}</a>
              </li>
            </ul>
          </div>
          <div>
            <h3 class="text-lg font-semibold mb-4">Business</h3>
            <ul class="space-y-2">
              <li v-for="(item, index) in businessLinks" :key="index">
                <a href="#" class="hover:text-yellow-600 transition-colors">{{ item }}</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { ref } from 'vue';

const quickLinks = ref([
  'Terms Of Use',
  'Terms & Conditions',
  'Refund Policy',
  'FASI',
  '404 Page'
]);

const companyLinks = ref([
  'About us',
  'Affiliate',
  'Career',
  'Contact us'
]);

const businessLinks = ref([
  'Our blog',
  'Cart',
  'My account',
  'Shop'
]);
</script>