<template>
  <div class="min-h-screen flex">
    <!-- Left: Login Card (full height, half width) -->
    <div class="flex flex-col justify-center items-center w-full md:w-1/3 bg-[#151d2a] px-8">
      <div class="w-full max-w-xs">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
          <span class="text-4xl font-bold text-white">mart<span class="text-yellow-400">fury</span></span>
          <h2 class="text-xl text-gray-300 mt-2">Sign In Below</h2>
        </div>
        <!-- Login Form -->
        <form @submit.prevent="handleLogin" class="space-y-6">
          <!-- Email/Username -->
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Email/Username <span class="text-red-500">*</span></label>
            <InputText 
              v-model="username" 
              class="w-full rounded bg-[#1e293b] border border-gray-600 text-gray-100 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
              placeholder="admin"
              required
            />
          </div>
          <!-- Password -->
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">Password <span class="text-red-500">*</span></label>
            <div class="flex items-center bg-[#1e293b] border border-gray-600 rounded px-2">
              <Password 
                v-model="password" 
                class="w-full bg-transparent text-gray-100 px-2 py-2 focus:outline-none" 
                placeholder="Password"
                :feedback="false"
                toggleMask
                required
              />
              <button type="button" class="text-gray-400 px-2">
                <!-- Eye icon here -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              </button>
            </div>
            <div class="flex justify-between mt-1">
              <label class="flex items-center text-sm text-gray-400">
                <Checkbox v-model="rememberMe" inputId="remember" :binary="true" class="mr-2" />
                Remember me?
              </label>
              <a href="#" class="text-sm text-blue-400 hover:underline">Lost your password?</a>
            </div>
          </div>
          <!-- Sign In Button -->
          <div class="pt-4">
            <Button 
              type="submit"
              label="Sign in" 
              class="w-full flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded mt-4"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              Sign in
            </Button>
          </div>
        </form>
      </div>
    </div>
    <!-- Right: Image (fills remaining space) -->
    <div class="hidden md:flex flex-1 h-screen relative">
      <img src="/images/adminlogin.jpg" alt="Login Side" class="object-cover w-full h-full" />
      <div class="absolute bottom-4 right-8 text-white text-right">
        <div class="font-bold">Botble Technologies</div>
        <div class="text-xs">Copyright 2023 © Botble Technologies. Version 1.4.0.6</div>
      </div>
    </div>
  </div>
  <Toast />
</template>

<script setup lang="ts"> 
import { ref } from 'vue';
import axios from 'axios';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Checkbox from 'primevue/checkbox';
import Button from 'primevue/button';
import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast'





// Replace with your actual background image path

const username = ref('');
const password = ref('');
const rememberMe = ref(false);
const error = ref('')
const toast = useToast()

async function handleLogin() {
  error.value = ''
  try {
    const response = await axios.post('http://127.0.0.1:8000/api/login', {
      username: username.value,
      password: password.value,
    })
    localStorage.setItem('token', response.data.token)
    toast.add({ severity: 'success',
                summary: 'Success', 
                detail: 'Login Successful',
                life:3000
              })
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Login failed'
    toast.add({ severity: 'error', 
                summary: 'Error', 
                detail: error.value, 
                life:3000
              })
  }
}
</script>

<style scoped>
.backdrop-blur-sm {
  backdrop-filter: blur(8px);
}

/* Custom style for password input */
:deep(.p-password-input) {
  width: 100%;
}
</style>