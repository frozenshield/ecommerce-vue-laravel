<template>
    <header class="w-full px-6 py-4 bg-white flex item-center justify-between shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-sm text-gray-400">Everything here</p>
        </div>

        <div class="flex-1 flex justify-center">

                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <router-link to="/search">
                            <i class="pi pi-search hover:text-yellow-500"></i>
                        </router-link>
                    </span>
                    <input type="text" placeholder="Search something" class="pl-10 pr-4 py-4 rounded-md border hover:border-yellow-500 border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent w-full md:w-96">
                </div>
         </div>

        <div class="flex items-center gap-4">
            <router-link to="/" class="text-sm text-gray-600 hover:text-yellow-500 flex items-center gap-2">
                VIEW YOUR STORE
                <i class="pi pi-external-link"></i>
            </router-link>
            
            <button 
                @click="handleLogout"
                class="text-sm text-gray-600 hover:text-red-500 flex items-center gap-2 transition-colors duration-200"
                title="Logout"
            >
                LOGOUT
                <i class="pi pi-sign-out"></i>
            </button>
        </div>
       
    </header>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import { clearAuthToken } from '../../utils/auth'

const router = useRouter()

const handleLogout = () => {
    // Show confirmation dialog
    if (confirm('Are you sure you want to logout?')) {
        // Clear the authentication token
        clearAuthToken()
        
        // Navigate to login page
        router.push('/adminlogin')
        
        // Optional: Show success message
        console.log('User logged out successfully')
    }
}
</script>