import { defineStore } from 'pinia'

interface User {
  id: number
  name: string
  email: string
  // Add more fields as needed
}

interface AuthState {
  isAuthenticated: boolean
  user: User | null
}

export const useAuthStore = defineStore('auth', {
  state: (): AuthState => ({
    isAuthenticated: !!localStorage.getItem('token'),
    user: null
  }),

  actions: {
    login(token: string, userData: User) {
      localStorage.setItem('token', token)
      this.isAuthenticated = true
      this.user = userData
    },

    logout() {
      localStorage.removeItem('token')
      this.isAuthenticated = false
      this.user = null
    },

    loadUser(userData: User) {
      this.user = userData
      this.isAuthenticated = true
    }
  }
})
