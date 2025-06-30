import { createRouter, createWebHistory } from 'vue-router'
import LoginComponent from '../components/LoginComponent.vue'
import LandingPage from '../components/LandingPage.vue'
import RegisterPage from '../components/RegisterComponent.vue'
import AdminLogin from '../components/admin/AdminLogin.vue'
import AdminLandingPage from '../components/admin/AdminLandingPage.vue'
import AdminProducts from '../components/admin/AdminProducts.vue'
import AdminAddProduct from '../components/admin/AdminAddProduct.vue'
import AdminOrders from '../components/admin/AdminOrders.vue'
import AdminCustomers from '../components/admin/AdminCustomers.vue'
import AdminCoupons from '../components/admin/AdminCoupons.vue'
import AdminCategories from '../components/admin/AdminCategories.vue'
import AdminSettings from '../components/admin/AdminSettings.vue'
import AdminDashboard from '../components/admin/AdminDashboard.vue'
import 'leaflet/dist/leaflet.css';

// main.ts
import 'primeicons/primeicons.css'

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
  {
    path: '/adminlogin',
    name: 'AdminLogin',
    component: AdminLogin,
  },
  {
    path: '/admin',
    name: 'Admin',
    component: AdminLandingPage,
    children: [
      {
        path: '',
        name: 'AdminDashboard',
        component: AdminDashboard,
      },
      {
        path: 'products',
        name: 'AdminProducts',
        component: AdminProducts,
      },
      {
        path: 'products/add',
        name: 'AdminAddProduct',
        component: AdminAddProduct,
      },
      {
        path: 'orders',
        name: 'AdminOrders',
        component: AdminOrders,
      },
      {
        path: 'customers',
        name: 'AdminCustomers',
        component: AdminCustomers,
      },
      {
        path: 'coupons',
        name: 'AdminCoupons',
        component: AdminCoupons,
      },
      {
        path: 'categories',
        name: 'AdminCategories',
        component: AdminCategories,
      },
      {
        path: 'settings',
        name: 'AdminSettings',
        component: AdminSettings,
      },
    ]
  }

]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Route guard for admin authentication
router.beforeEach((to, _from, next) => {
  // Check if the route requires authentication (any admin route)
  if (to.path.startsWith('/admin') && to.path !== '/adminlogin') {
    const token = localStorage.getItem('token')
    
    if (!token) {
      // No token found, redirect to admin login
      console.log('No authentication token found, redirecting to admin login')
      next('/adminlogin')
      return
    }
    
    // Token exists, allow access
    next()
  } else {
    // Non-admin route or admin login page, allow access
    next()
  }
})

export default router