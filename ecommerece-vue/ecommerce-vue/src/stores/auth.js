import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        isAuthenticated: !!localStorage.getItem('token'),
        user:null
    }),
    actions: {
        login(token, user) {
            localStorage.setItem('token', token)
            this.isAuthenticated = true
            this.user = userData
        },
        logout() {
            localStorage.removeItem('token')
            this.isAuthenticated = false
            this.user = null
        },
        loadUser(userData) {
            this.user = userData
            this.isAuthenticated = true
        }
    }
})