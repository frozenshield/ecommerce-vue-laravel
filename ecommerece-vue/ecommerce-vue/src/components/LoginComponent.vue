<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-md">
      <div class="text-center">
        <h1 class="text-3xl font-bold text-gray-900">Login to your account</h1>
        <p class="mt-2 text-sm text-gray-600">
          Your personal data will be used to support your experience throughout this website, to manage access to your account.
        </p>
      </div>

      <div class="mt-8 space-y-6">
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Username</label>
            <InputText 
              type="text" 
              v-model="username" 
              class="w-full mt-1" 
              placeholder="Enter your email"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <Password 
              v-model="password" 
              :feedback="false" 
              toggleMask 
              class="w-full mt-1" 
              placeholder="Enter your password"
              inputClass="w-full"
            />
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <Checkbox v-model="rememberMe" inputId="remember" :binary="true" />
              <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
            </div>
            <router-link to="/forgot-password" class="text-sm text-yellow-500 hover:text-yellow-600">
              Forgot password?
            </router-link>
          </div>
        </div>

        <Button 
          label="Login" 
          icon="pi pi-sign-in" 
          class="w-full justify-center bg-yellow-500 hover:bg-yellow-600 border-yellow-500"
          @click="onLogin"
        />

        <div class="text-center text-sm">
          <span class="text-gray-600">Don't have an account?</span>
          <router-link to="/register" class="ml-1 text-yellow-500 hover:text-yellow-600 font-medium">
            Register now
          </router-link>
        </div>
      </div>

      <div class="relative">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
          <span class="px-2 bg-white text-gray-500">or login with</span>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <Button 
          icon="pi pi-facebook" 
          class="p-button-outlined justify-center bg-blue-600 text-white hover:bg-blue-700"
          label="Facebook"
        />
        <Button 
          icon="pi pi-google" 
          class="p-button-outlined justify-center bg-red-600 text-white hover:bg-red-700"
          label="Google"
        />
        <Button 
          icon="pi pi-github" 
          class="p-button-outlined justify-center bg-gray-800 text-white hover:bg-gray-900"
          label="GitHub"
        />
        <Button 
          icon="pi pi-linkedin" 
          class="p-button-outlined justify-center bg-blue-500 text-white hover:bg-blue-600"
          label="LinkedIn"
        />
      </div>
    </div>
    <Toast />
  </div>
<Footer />
</template>

<script setup lang="ts">
import Footer from '../components/Footer.vue';
import { ref } from 'vue';
import axios from 'axios'
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Checkbox from 'primevue/checkbox';
import Button from 'primevue/button';
import { useRouter } from 'vue-router';
import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast'

const username = ref('');
const password = ref('');
const rememberMe = ref(false);
const error = ref('')
const router = useRouter();
const toast = useToast()

async function onLogin() {
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
/* Custom styles */
:deep(.p-password-input) {
  width: 100%;
}
</style>