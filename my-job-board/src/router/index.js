import { createRouter, createWebHistory } from 'vue-router'
import Home from '@/views/Home.vue'
import JobSearch from '@/views/JobSearch.vue'
import JobDetails from '@/views/JobDetails.vue'
import EmployerDashboard from '@/views/EmployerDashboard.vue'
import AdminDashboard from '@/views/AdminPanel.vue'
import Register from '@/views/auth/Register.vue'
import Login from '@/views/auth/Login.vue'
import { useAuth } from '@/composables/useAuth'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
  },
  {
    path: '/jobs',
    name: 'JobSearch',
    component: JobSearch,
  },
  {
    path: '/jobs/:id',
    name: 'JobDetails',
    component: JobDetails,
  },
  {
    path: '/employer',
    name: 'EmployerDashboard',
    component: EmployerDashboard,
    meta: { requiresAuth: true, role: 'employer' },
  },
  {
    path: '/admin',
    name: 'AdminDashboard',
    component: AdminDashboard,
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

router.beforeEach((to, from, next) => {
  const { isAuthenticated, user } = useAuth()

  console.log('Navigating to:', to.path, 'isAuthenticated:', isAuthenticated.value)

  if (to.meta.requiresAuth && !isAuthenticated.value) {
    return next('/login')
  }

  if (to.meta.role && user.value?.role !== to.meta.role) {
    return next('/')
  }

  if (['/login', '/register'].includes(to.path) && isAuthenticated.value) {
    return next('/')
  }

  next()
})

export default router
