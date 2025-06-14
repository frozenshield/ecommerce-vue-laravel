<template>
  <div class="bg-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
      <!-- Main Content -->
      <div class="flex flex-col md:flex-row items-center gap-8">
        <!-- Left Column - Text Content -->
        <div class="flex-1 space-y-6">
          <h2 class="text-3xl font-bold text-gray-900">Download Marifury App Now!</h2>
          <p class="text-gray-600">
            Shopping fastly and easily more with our app. Get a link to download the app on your phone.
          </p>
          
          <!-- Email Subscription -->
          <div class="flex flex-col sm:flex-row gap-4">
            <input
              type="email"
              placeholder="Email Address"
              class="flex-1 px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
            >
            <button
              class="bg-yellow-500 text-white px-6 py-3 rounded-md hover:bg-yellow-600 transition-colors whitespace-nowrap"
            >
              Subscribe
            </button>
          </div>
          
          <!-- App Store Badges -->
          <div class="flex gap-4">
            <a href="#" class="inline-block">
              <img src="/images/play-store-badge.png" alt="Google Play" class="w-36 h-auto" />
            </a>
            <a href="#" class="inline-block">
              <img src="/images/app-store-badge.png" alt="App Store" class="w-36 h-auto" />
            </a>
          </div>
        </div>
        
        <!-- Right Column - Brand Text -->
        <div class="flex-1 text-center md:text-right">
          <div class="text-5xl font-bold text-gray-900 mb-2">russfury</div>
          <div class="text-xl text-gray-600 space-y-1">
            <div>MOVY SUMMER</div>
            <div>LOVING SUMPS CO</div>
            <div class="text-yellow-600 font-medium">UP TO 40% OFF</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div>
    <Footer />
  </div>
</template>

<style scoped>
/* Custom styles if needed */
</style>