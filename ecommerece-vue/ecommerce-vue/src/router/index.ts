import { createRouter, createWebHistory } from 'vue-router'
import LoginComponent from '../components/LoginComponent.vue'
import LandingPage from '../components/LandingPage.vue'
import RegisterPage from '../components/RegisterComponent.vue'
// import RegisterComponent from '../components/RegisterComponent.vue' // Uncomment if you have this

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: LoginComponent,
  },
  {
    path: '/',
    name: 'Landing',
    component: LandingPage,
  },
  {
    path: '/register',
    name: 'Register',
    component: RegisterPage,
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router