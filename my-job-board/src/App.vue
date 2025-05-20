<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <nav class="bg-white dark:bg-gray-800 shadow-modern sticky top-0 z-50">
      <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        <router-link to="/" class="text-2xl font-bold text-primary-light dark:text-primary-dark">
          JobBoard
        </router-link>
        <div class="flex items-center gap-4">
          <router-link to="/jobs" class="text-gray-600 dark:text-gray-300 hover:text-primary-light dark:hover:text-primary-dark transition">
            Browse Jobs
          </router-link>
          
          <!-- Show these links only when NOT authenticated -->
          <template v-if="!isAuthenticated">
            <router-link to="/login" class="text-gray-600 dark:text-gray-300 hover:text-primary-light dark:hover:text-primary-dark transition">
              Login
            </router-link>
            <router-link to="/register" class="text-gray-600 dark:text-gray-300 hover:text-primary-light dark:hover:text-primary-dark transition">
              Register
            </router-link>
          </template>
          
          <!-- Show these links only when authenticated -->
          <template v-if="isAuthenticated">
            <router-link v-if="user?.role === 'employer'" to="/employer" class="text-gray-600 dark:text-gray-300 hover:text-primary-light dark:hover:text-primary-dark transition">
              Dashboard
            </router-link>
            <router-link v-if="user?.role === 'admin'" to="/admin" class="text-gray-600 dark:text-gray-300 hover:text-primary-light dark:hover:text-primary-dark transition">
              Admin Panel
            </router-link>
            <button @click="logout" class="text-gray-600 dark:text-gray-300 hover:text-red-500 transition">
              Logout
            </button>
          </template>
          
          <button @click="toggleDarkMode" class="p-2 rounded-full bg-gray-200 dark:bg-gray-700">
            <svg v-if="!isDarkMode" class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <svg v-else class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
          </button>
        </div>
      </div>
    </nav>
    <main class="pt-6">
      <router-view class="fade-in"></router-view>
    </main>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useAuthStore } from './stores/auth'
import { storeToRefs } from 'pinia'

const authStore = useAuthStore()
const { isAuthenticated, user } = storeToRefs(authStore)
const logout = authStore.logout

const isDarkMode = computed(() => document.documentElement.classList.contains('dark'))

const toggleDarkMode = () => {
  document.documentElement.classList.toggle('dark')
  localStorage.setItem('theme', isDarkMode.value ? 'light' : 'dark')
}

if (localStorage.getItem('theme') === 'dark') {
  document.documentElement.classList.add('dark')
}
</script>


