<template>
  <div class="dashboard-container bg-gray-50 min-h-screen p-6">
    <!-- Header Section -->
    <div class="mb-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-900">DASHBOARD</h1>
        <Button label="Manage Widgets" icon="pi pi-cog" class="p-button-outlined" />
      </div>
      
      <!-- Demo Site Notice -->
      <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
        <div class="flex items-center">
          <i class="pi pi-info-circle text-blue-400 mr-2"></i>
          <p class="text-blue-700">
            <strong>Hi guest,</strong> if your demo site is destroyed, please help me <a href="#" class="text-blue-600 underline">go here</a> and restore demo site to the latest revision! Thank you so much!
          </p>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="stats-card bg-cyan-400 text-white p-6 rounded-lg">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-sm font-medium opacity-90">Orders</h3>
            <p class="text-3xl font-bold">50</p>
          </div>
          <i class="pi pi-shopping-cart text-2xl opacity-80"></i>
        </div>
      </div>
      
      <div class="stats-card bg-blue-500 text-white p-6 rounded-lg">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-sm font-medium opacity-90">Products</h3>
            <p class="text-3xl font-bold">54</p>
          </div>
          <i class="pi pi-box text-2xl opacity-80"></i>
        </div>
      </div>
      
      <div class="stats-card bg-blue-300 text-white p-6 rounded-lg">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-sm font-medium opacity-90">Customers</h3>
            <p class="text-3xl font-bold">10</p>
          </div>
          <i class="pi pi-users text-2xl opacity-80"></i>
        </div>
      </div>
      
      <div class="stats-card bg-blue-800 text-white p-6 rounded-lg">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-sm font-medium opacity-90">Reviews</h3>
            <p class="text-3xl font-bold">461</p>
          </div>
          <i class="pi pi-star text-2xl opacity-80"></i>
        </div>
      </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mb-8">
      <!-- Site Analytics Chart -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Site Analytics</h3>
          <Dropdown v-model="selectedPeriod" :options="periods" optionLabel="label" optionValue="value" class="w-32" />
        </div>
        <div class="chart-container h-64">
          <Chart type="line" :data="chartData" :options="chartOptions" class="h-full" />
        </div>
      </div>
    </div>

    <!-- Analytics Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow-sm p-6 text-center">
        <div class="flex items-center justify-center mb-2">
          <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center">
            <i class="pi pi-eye text-pink-600 text-xl"></i>
          </div>
        </div>
        <p class="text-sm text-gray-600 mb-1">Sessions</p>
        <p class="text-2xl font-bold text-gray-900">674</p>
      </div>
      
      <div class="bg-white rounded-lg shadow-sm p-6 text-center">
        <div class="flex items-center justify-center mb-2">
          <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
            <i class="pi pi-users text-green-600 text-xl"></i>
          </div>
        </div>
        <p class="text-sm text-gray-600 mb-1">Visitors</p>
        <p class="text-2xl font-bold text-gray-900">580</p>
      </div>
      
      <div class="bg-white rounded-lg shadow-sm p-6 text-center">
        <div class="flex items-center justify-center mb-2">
          <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
            <i class="pi pi-file text-blue-600 text-xl"></i>
          </div>
        </div>
        <p class="text-sm text-gray-600 mb-1">Pageviews</p>
        <p class="text-2xl font-bold text-gray-900">2,037</p>
      </div>
      
      <div class="bg-white rounded-lg shadow-sm p-6 text-center">
        <div class="flex items-center justify-center mb-2">
          <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
            <i class="pi pi-bolt text-orange-600 text-xl"></i>
          </div>
        </div>
        <p class="text-sm text-gray-600 mb-1">Bounce Rate</p>
        <p class="text-2xl font-bold text-gray-900">88%</p>
      </div>
    </div>

    <!-- Traffic Sources -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-8">
      <div class="lg:col-span-1 bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Traffic Sources</h3>
        <div class="h-48 mb-4">
          <Chart type="doughnut" :data="donutChartData" :options="donutChartOptions" class="h-full" />
        </div>
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
              <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
              <span class="text-xs text-gray-600">Direct</span>
            </div>
            <span class="text-xs font-semibold">35%</span>
          </div>
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
              <div class="w-3 h-3 bg-green-500 rounded-full"></div>
              <span class="text-xs text-gray-600">Search</span>
            </div>
            <span class="text-xs font-semibold">25%</span>
          </div>
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
              <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
              <span class="text-xs text-gray-600">Social</span>
            </div>
            <span class="text-xs font-semibold">20%</span>
          </div>
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
              <div class="w-3 h-3 bg-red-500 rounded-full"></div>
              <span class="text-xs text-gray-600">Email</span>
            </div>
            <span class="text-xs font-semibold">10%</span>
          </div>
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
              <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
              <span class="text-xs text-gray-600">Referral</span>
            </div>
            <span class="text-xs font-semibold">10%</span>
          </div>
        </div>
      </div>
      
      <!-- World Map (larger section) -->
      <div class="lg:col-span-4 bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Visitors by Country</h3>
        <div class="h-64 relative">
          <div id="worldMap" class="w-full h-full rounded"></div>
        </div>
      </div>
    </div>

    <!-- Tables Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <!-- Top Most Visit Pages -->
      <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
          <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Top Most Visit Pages</h3>
            <Dropdown v-model="selectedPagesPeriod" :options="periods" optionLabel="label" optionValue="value" class="w-32" />
          </div>
        </div>
        <div class="p-0">
          <DataTable :value="topPages" class="border-none">
            <Column field="rank" header="#" class="w-12"></Column>
            <Column field="page" header="PAGE"></Column>
            <Column field="views" header="VIEWS" class="text-right w-20"></Column>
          </DataTable>
        </div>
      </div>

      <!-- Top Browsers -->
      <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
          <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Top Browsers</h3>
            <Dropdown v-model="selectedBrowsersPeriod" :options="periods" optionLabel="label" optionValue="value" class="w-32" />
          </div>
        </div>
        <div class="p-0">
          <DataTable :value="topBrowsers" class="border-none">
            <Column field="rank" header="#" class="w-12"></Column>
            <Column field="browser" header="BROWSER"></Column>
            <Column field="sessions" header="SESSIONS" class="text-right w-20"></Column>
          </DataTable>
        </div>
      </div>
    </div>

    <!-- Bottom Tables Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Top Referrers -->
      <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
          <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Top Referrers</h3>
            <Dropdown v-model="selectedReferrersPeriod" :options="periods" optionLabel="label" optionValue="value" class="w-32" />
          </div>
        </div>
        <div class="p-0">
          <DataTable :value="topReferrers" class="border-none">
            <Column field="rank" header="#" class="w-12"></Column>
            <Column field="url" header="URL"></Column>
            <Column field="count" header="COUNT" class="text-right w-20"></Column>
          </DataTable>
        </div>
      </div>

      <!-- Recent Posts -->
      <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Recent Posts</h3>
        </div>
        <div class="p-0">
          <DataTable :value="recentPosts" class="border-none">
            <Column field="rank" header="#" class="w-12"></Column>
            <Column field="title" header="TITLE"></Column>
            <Column field="date" header="CREATED AT" class="text-right w-32"></Column>
          </DataTable>
        </div>
      </div>

      <!-- Activities and Ecommerce -->
      <div class="space-y-6">
        <!-- Activities Logs -->
        <div class="bg-white rounded-lg shadow-sm">
          <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Activities Logs</h3>
          </div>
          <div class="p-4">
            <div v-for="activity in activities" :key="activity.id" class="flex items-start space-x-3 mb-4">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                  <i class="pi pi-user text-yellow-600 text-sm"></i>
                </div>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900">{{ activity.user }}</p>
                <p class="text-xs text-gray-500">{{ activity.action }}</p>
                <p class="text-xs text-gray-400">{{ activity.time }}</p>
              </div>
            </div>
            <p class="text-xs text-gray-500 text-center mt-4">Showing 1 to 2 of 2 records</p>
          </div>
        </div>

        <!-- Ecommerce -->
        <div class="bg-white rounded-lg shadow-sm">
          <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Ecommerce</h3>
          </div>
          <div class="p-4 space-y-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-2">
                <i class="pi pi-dollar text-green-500"></i>
                <span class="text-sm text-gray-600">Revenue</span>
              </div>
              <span class="font-semibold">$73,586.06</span>
            </div>
            
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-2">
                <i class="pi pi-shopping-cart text-blue-500"></i>
                <span class="text-sm text-gray-600">Orders</span>
              </div>
              <span class="font-semibold">24</span>
            </div>
            
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-2">
                <i class="pi pi-box text-purple-500"></i>
                <span class="text-sm text-gray-600">Products</span>
              </div>
              <span class="font-semibold">26</span>
            </div>
            
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-2">
                <i class="pi pi-percentage text-yellow-500"></i>
                <span class="text-sm text-gray-600">Conversion</span>
              </div>
              <span class="font-semibold">0</span>
            </div>
            
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-2">
                <i class="pi pi-exclamation-triangle text-red-500"></i>
                <span class="text-sm text-gray-600">Refunds</span>
              </div>
              <span class="font-semibold">0</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Button from 'primevue/button'
import Dropdown from 'primevue/dropdown'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Chart from 'primevue/chart'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

// Dropdown options
const periods = ref([
  { label: 'Today', value: 'today' },
  { label: 'Yesterday', value: 'yesterday' },
  { label: 'Last 7 days', value: '7days' },
  { label: 'Last 30 days', value: '30days' }
])

const selectedPeriod = ref('today')
const selectedPagesPeriod = ref('today')
const selectedBrowsersPeriod = ref('today')
const selectedReferrersPeriod = ref('today')

// Sample data
const topPages = ref([
  { rank: 1, page: 'Shopify - Multipurpose eCommerce Laravel Script', views: 136 },
  { rank: 2, page: 'Showcasing Creative Designs and Innovative Projects', views: 75 },
  { rank: 3, page: 'Botiga - Laravel Personal Blog Script', views: 67 },
  { rank: 4, page: 'Botiga', views: 65 },
  { rank: 5, page: 'Web & App developer', views: 60 },
  { rank: 6, page: 'Farmart - Laravel Ecommerce system', views: 57 },
  { rank: 7, page: 'Shopping Cart', views: 45 },
  { rank: 8, page: 'Martfury - Laravel Ecommerce system', views: 44 },
  { rank: 9, page: 'Shopping Cart', views: 43 },
  { rank: 10, page: 'Shopping Cart', views: 28 }
])

const topBrowsers = ref([
  { rank: 1, browser: 'Chrome', sessions: 537 },
  { rank: 2, browser: 'Edge', sessions: 46 },
  { rank: 3, browser: 'Firefox', sessions: 38 },
  { rank: 4, browser: 'Safari', sessions: 29 },
  { rank: 5, browser: 'Opera', sessions: 17 },
  { rank: 6, browser: 'Android Webview', sessions: 3 },
  { rank: 7, browser: 'Samsung Internet', sessions: 3 },
  { rank: 8, browser: 'Safari (in-app)', sessions: 2 }
])

const topReferrers = ref([
  { rank: 1, url: '(not set)', count: 1216 },
  { rank: 2, url: '(direct)', count: 628 },
  { rank: 3, url: 'codecanyon.net', count: 286 },
  { rank: 4, url: 'google', count: 30 },
  { rank: 5, url: 'aromatic-handbag.shop', count: 10 },
  { rank: 6, url: 'github.com', count: 10 },
  { rank: 7, url: 'portfolio.creativedesign.com.bd', count: 5 },
  { rank: 8, url: 'sezrexitpay.com', count: 4 },
  { rank: 9, url: 'yandar.ru', count: 4 },
  { rank: 10, url: 'zaman.xyz.tf', count: 3 }
])

const recentPosts = ref([
  { rank: 1, title: '4 Expert Tips On How To Choose The Right Men\'s Wallet', date: '2025-05-26' },
  { rank: 2, title: 'Sexy Clutches: How to Buy & Wear a Designer Clutch Bag', date: '2025-05-26' },
  { rank: 3, title: 'The Top 2020 Handbag Trends to Know', date: '2025-05-26' },
  { rank: 4, title: 'How to Match the Color of Your Handbag With an Outfit', date: '2025-05-26' },
  { rank: 5, title: 'How to Care for Leather Bags', date: '2025-05-26' },
  { rank: 6, title: 'We\'re Crushing Hard on Summer\'s 10 Biggest Bag Trends', date: '2025-05-26' },
  { rank: 7, title: 'Essential Qualities of Highly Successful Music', date: '2025-05-26' },
  { rank: 8, title: '9 Things I Love About Shaving My Head', date: '2025-05-26' },
  { rank: 9, title: 'Why Teamwork Really Makes The Dream Work', date: '2025-05-26' },
  { rank: 10, title: 'The World Caters to Average People', date: '2025-05-26' }
])

const activities = ref([
  {
    id: 1,
    user: 'Pearline Borer',
    action: 'logged in to the system',
    time: '31 minutes ago (17:31:51 03/06)'
  },
  {
    id: 2,
    user: 'System',
    action: 'restored backup',
    time: '37 minutes ago (17:25:51)'
  }
])

// Chart data and options
const chartData = ref({
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
  datasets: [
    {
      label: 'Sessions',
      data: [420, 380, 450, 520, 480, 620, 580, 680, 720, 840, 920, 1050],
      borderColor: '#3B82F6',
      backgroundColor: 'rgba(59, 130, 246, 0.1)',
      tension: 0.4,
      fill: true
    },
    {
      label: 'Visitors',
      data: [320, 280, 350, 420, 380, 520, 480, 580, 620, 740, 820, 950],
      borderColor: '#10B981',
      backgroundColor: 'rgba(16, 185, 129, 0.1)',
      tension: 0.4,
      fill: true
    }
  ]
})

const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: true,
      position: 'top'
    }
  },
  scales: {
    x: {
      grid: {
        display: false
      }
    },
    y: {
      beginAtZero: true,
      grid: {
        color: 'rgba(0, 0, 0, 0.1)'
      }
    }
  }
})

// Donut chart for traffic sources
const donutChartData = ref({
  labels: ['Direct', 'Search', 'Social', 'Email', 'Referral'],
  datasets: [
    {
      data: [35, 25, 20, 10, 10],
      backgroundColor: [
        '#3B82F6', // Blue
        '#10B981', // Green
        '#F59E0B', // Yellow
        '#EF4444', // Red
        '#8B5CF6'  // Purple
      ],
      borderWidth: 0,
      hoverOffset: 4
    }
  ]
})

const donutChartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false // We'll show custom legend below
    }
  },
  cutout: '60%' // Makes it a donut instead of pie
})

// Donut chart for traffic sources - replaced with world map
// World map data
const worldMapData = ref([
  { country: 'United States', visitors: 1234, lat: 39.8283, lng: -98.5795 },
  { country: 'India', visitors: 856, lat: 20.5937, lng: 78.9629 },
  { country: 'United Kingdom', visitors: 643, lat: 55.3781, lng: -3.4360 },
  { country: 'Canada', visitors: 432, lat: 56.1304, lng: -106.3468 },
  { country: 'Germany', visitors: 321, lat: 51.1657, lng: 10.4515 },
  { country: 'Australia', visitors: 287, lat: -25.2744, lng: 133.7751 },
  { country: 'Brazil', visitors: 234, lat: -14.2350, lng: -51.9253 },
  { country: 'France', visitors: 198, lat: 46.2276, lng: 2.2137 }
])

// Initialize world map
onMounted(() => {
  initWorldMap()
})

const initWorldMap = () => {
  const map = L.map('worldMap', {
    zoomControl: true,
    scrollWheelZoom: false,
    doubleClickZoom: false,
    touchZoom: false,
    dragging: true
  }).setView([20, 0], 2)

  // Add tile layer
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 18
  }).addTo(map)

  // Add markers for each country
  worldMapData.value.forEach(country => {
    const markerSize = Math.max(8, Math.min(25, country.visitors / 50))
    
    const marker = L.circleMarker([country.lat, country.lng], {
      radius: markerSize,
      fillColor: getCountryColor(country.visitors),
      color: '#fff',
      weight: 2,
      opacity: 1,
      fillOpacity: 0.8
    }).addTo(map)
    
    marker.bindPopup(`
      <div class="text-center">
        <strong>${country.country}</strong><br>
        <span class="text-blue-600">${country.visitors.toLocaleString()} visitors</span>
      </div>
    `)
  })
}

const getCountryColor = (visitors: number) => {
  if (visitors > 800) return '#1E40AF' // Dark blue
  if (visitors > 500) return '#3B82F6' // Blue
  if (visitors > 300) return '#60A5FA' // Light blue
  if (visitors > 200) return '#93C5FD' // Very light blue
  return '#DBEAFE' // Lightest blue
}
</script>

<style scoped>
.dashboard-container {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.stats-card {
  transition: transform 0.2s ease-in-out;
}

.stats-card:hover {
  transform: translateY(-2px);
}

.chart-container {
  background: white;
  border-radius: 0.5rem;
}

.chart-container canvas {
  border-radius: 0.5rem;
}

:deep(.p-datatable .p-datatable-thead > tr > th) {
  background: #f8f9fa;
  color: #6c757d;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.75rem;
  padding: 1rem 1.5rem;
  border: none;
}

:deep(.p-datatable .p-datatable-tbody > tr > td) {
  padding: 1rem 1.5rem;
  border: none;
  border-bottom: 1px solid #e9ecef;
}

:deep(.p-datatable .p-datatable-tbody > tr:hover) {
  background: #f8f9fa;
}

:deep(.p-dropdown) {
  border: 1px solid #dee2e6;
}

/* Leaflet map styling */
#worldMap {
  z-index: 1;
}

:deep(.leaflet-container) {
  border-radius: 0.5rem;
}

:deep(.leaflet-popup-content-wrapper) {
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

:deep(.leaflet-popup-content) {
  margin: 8px 12px;
  font-size: 14px;
}

:deep(.leaflet-control-zoom) {
  border: none !important;
  border-radius: 0.5rem !important;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
}

:deep(.leaflet-control-zoom a) {
  border-radius: 0.25rem !important;
  color: #374151 !important;
  background: white !important;
  border: 1px solid #e5e7eb !important;
}
</style>
