<template>
  <div class="flex justify-center items-center min-h-screen bg-gray-100">
    <Card style="width: 25rem; overflow: hidden">
      <template #title>Login</template>
      <template #content>
        <form @submit.prevent="onLogin">
          <div class="mb-4">
            <label>Username</label>
            <InputText id="username" v-model="username" class="block w-full" />
          </div>
          <div class="mb-4">
            <label>Password</label>      
            <Password id="password" v-model="password" class="block w-full" inputClass="w-full" />
          </div>
          <Button label="login" type="submit" class="w-full" />
        </form>
      </template>
    </Card>
    <Toast />
  </div>
</template>


<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Password from 'primevue/password'
import Button from 'primevue/button'
import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast'

const username = ref('')
const password = ref('')
const error = ref('')

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

