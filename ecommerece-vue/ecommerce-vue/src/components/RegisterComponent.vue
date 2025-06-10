<template>
  <div class="flex justify-center items-center min-h-screen bg-gray-100">
    <Card style="width: 25rem; overflow: hidden">
      <template #title>Register</template>
      <template #content>
        <form @submit.prevent="onRegister">
          <div class="mb-4">
            <label>Username</label>
            <InputText id="username" v-model="username" class="block w-full" />
          </div>
          <div class="mb-4">
            <label>Email</label>
            <InputText id="email" v-model="email" type="email" class="block w-full" />
          </div>
          <div class="mb-4">
            <label>Password</label>      
            <Password id="password" v-model="password" class="block w-full" inputClass="w-full" />
          </div>
          <div class="mb-4">
            <label>Role</label>
            <Dropdown
                v-model="role"
                :options="roles"
                optionLabel="label"
                optionValue="value"
                placeholder="Select a role"
                class="w-full"
            />
          </div>
          <Button label="Register" type="submit" class="w-full" />
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
import Dropdown from 'primevue/dropdown'
import { useToast } from 'primevue/usetoast'
import { useRouter } from 'vue-router'

const router = useRouter()
const username = ref('')
const password = ref('')
const email = ref('')
const error = ref('')
const role = ref('')

const roles = [
    { label: 'Customer', value: 'customer'},
    { label: 'seller', value: 'seller'}
]

const toast = useToast()

async function onRegister() {
  error.value = ''
  try {
      await axios.post('http://127.0.0.1:8000/api/users/register', {
      username: username.value,
      email: email.value,
      password: password.value,
      role: role.value
    }); toast.add({
        severity: 'success',
        summary: 'Success',
        detail: 'Registration successful!',
        life: 3000
    })
    setTimeout(() => {
        router.push('/')
    }, 3000);    
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Register failed'
    toast.add({ severity: 'error', 
                summary: 'Error', 
                detail: error.value, 
                life:3000
              })
  }
}
</script>

